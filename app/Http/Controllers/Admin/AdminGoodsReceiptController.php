<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GoodsIssue;
use App\Models\GoodsReceipt;
use App\Models\GoodsReceiptDetail;
use App\Models\Product;
use App\Models\Provider;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminGoodsReceiptController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $goodsReceipt;
    protected $warehouse;
    protected $user;
    protected $provider;
    protected $product;
    protected $purchaseOrder;
    protected $purchaseOrderDetail;
    public function __construct(
        GoodsReceipt $goodsReceipt,
        Warehouse $warehouse,
        User $user,
        Provider $provider,
        Product $product,
        PurchaseOrder $purchaseOrder,
        PurchaseOrderDetail $purchaseOrderDetail
    ) {
        $this->goodsReceipt = $goodsReceipt;
        $this->warehouse = $warehouse;
        $this->user = $user;
        $this->provider = $provider;
        $this->product = $product;
        $this->purchaseOrder = $purchaseOrder;
        $this->purchaseOrderDetail = $purchaseOrderDetail;
    }
    public function index()
    {
        $goodsReceipts = $this->goodsReceipt->all();
        $approvedProducts = $this->product->whereHas('restockRequestDetails', function ($query) {
            $query->where('status', 'approved');
        })->select('id', 'code', 'name', 'provider_id', 'unit_id')
            ->with([
                'provider:id,name',
                'unit:id,name',
                'restockRequestDetails' => function ($query) {
                    $query->where('status', 'approved')->with('restockRequest:id,warehouse_id');
                }
            ])
            ->get()
            ->groupBy('provider_id')
            ->map(function ($products, $key) {
                $providerName = $products->first()->provider->name ?? 'Không có dữ liệu tên';
                $providerId = $products->first()->provider->id;
                // $productIds = $products->pluck('id')->toArray();
                return [
                    'provider_name' => $providerName,
                    'provider_id' => $providerId,
                    // 'product_ids' => $productIds,
                    'products' => $products->map(function ($product) {
                        $restockDetails = $product->restockRequestDetails->map(function ($query) {
                            return [
                                'warehouse_name' => $query->restockRequest->warehouse->name,
                                'quantity' => $query->quantity,
                                'product_id' => $query->product_id
                            ];
                        });
                        $totalQuantity = $restockDetails->sum('quantity');
                        return [
                            'id' => $product->id,
                            'code' => $product->code,
                            'name' => $product->name,
                            'unit' => $product->unit->name,
                            'restock_details' => $restockDetails,
                            'totalQuantity' => $totalQuantity
                        ];
                    })->values()
                ];
            })->values();
        info($approvedProducts);
        return view('admin.goods-receipts.index', compact('goodsReceipts', 'approvedProducts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // dd($request->all());
        $provider = $this->provider->findOrFail($request->provider_id);
        $approvedProduct = $request->products;
        // $approvedProduct = $this->product
        //     ->whereIn('id', $products)
        //     ->whereHas('restockRequestDetails', function ($query) {
        //         $query->where('status', 'approved');
        //     })
        //     ->select('id', 'code', 'name', 'unit_id')
        //     ->with(['unit:id,name', 'restockRequestDetails'])->get();
        info($approvedProduct);
        $warehouses = $this->warehouse->all();
        $providers = $this->provider->all();
        $creators = $this->user->all();
        $lastestCode = $this->goodsReceipt::latest('id')->first();
        if ($lastestCode) {
            $lastNumber = (int)substr($lastestCode->code, 3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $newCode = 'PMH' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
        return view('admin.goods-receipts.create', compact(
            'warehouses',
            'providers',
            'creators',
            'newCode',
            'provider',
            'approvedProduct'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        dd($request->all());
        DB::beginTransaction();
        $user_id = $request->creator_id;
        $provider_id = $request->provider_id;
        $code = $request->code;
        $products = $request->products;
        $purchase_order = $this->purchaseOrder->create([
            'code' => $code,
            'provider_id' => $provider_id,
            'user_id' => $user_id,
            'status' => 'created'
        ]);
        $orderedProducIds = [];
        foreach ($products as $productId => $productData) {
            $product = $this->product->findOrFail($productId);
            if ($product) {
                $this->purchaseOrderDetail->create([
                    'purchase_order_id' => $purchase_order->id,
                    'product_id' => $product->id,
                    'quantity' => $productData['quantity'],
                    'unit_price' => $productData['unit_price'],
                    'discount' => $productData['discount']
                ]);
                $orderedProducIds[] = $product->id;
            }
        }

        if (!empty($orderedProducIds)) {
            DB::table('restock_request_details')->whereIn('product_id', $orderedProducIds)->update(['status' => 'ordered']);
        }

        DB::commit();
        return to_route('goodsreceipts.index')->with(['message' => 'Tạo phiếu mua hàng thành công!']);
    }

    public function storeReceipt(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();

        try {
            $lastGoodsReceipt = GoodsReceipt::latest('id')->first();
            $lastCode = $lastGoodsReceipt ? $lastGoodsReceipt->code : 'PNH0000';
            $nextCodeNumber = intval(substr($lastCode, 3)) + 1;

            foreach ($request->distributions as $distribution) {
                if (!is_array($distribution)) {
                    throw new \Exception("Dữ liệu phân phối không hợp lệ. Giá trị phân phối không phải là mảng.");
                }

                if (!isset($distribution['warehouse_id'], $distribution['quantity'], $distribution['product_id'], $distribution['unit_price'], $distribution['discount'])) {
                    throw new \Exception("Thông tin chi tiết phân phối không hợp lệ.");
                }

                $newCode = 'PNH' . str_pad($nextCodeNumber, 4, '0', STR_PAD_LEFT);
                $nextCodeNumber++;

                $goodsReceipt = GoodsReceipt::create([
                    'code' => $newCode,
                    'provider_id' => $request->provider_id,
                    'creator_id' => $request->user_id,
                    'warehouse_id' => $distribution['warehouse_id'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                GoodsReceiptDetail::create([
                    'goods_receipt_id' => $goodsReceipt->id,
                    'product_id' => $distribution['product_id'],
                    'quantity' => $distribution['quantity'],
                    'unit_price' => $distribution['unit_price'],
                    'discount' => $distribution['discount'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();
            return redirect()->route('goodsreceipts.index')->with('success', 'Phiếu nhận hàng đã được tạo thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Đã xảy ra lỗi khi tạo phiếu nhận hàng: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show() {}

    public function display()
    {
        $purchaseOrders = $this->purchaseOrder::with([
            'purchaseOrderDetails.product' => function ($query) {
                $query->select('id', 'code', 'name', 'unit_id')->with('unit:id,name');
            },
            'provider:id,name',
        ])->latest('id')->get();

        $productIds = $purchaseOrders->flatMap(function ($order) {
            return $order->purchaseOrderDetails->pluck('product_id');
        });

        $distributionData = DB::table('restock_request_details')
            ->join('restock_requests', 'restock_request_details.restock_request_id', '=', 'restock_requests.id')
            ->whereIn('restock_request_details.product_id', $productIds)
            ->where('restock_request_details.status', 'ordered')
            ->select(
                'restock_request_details.product_id',
                'restock_request_details.quantity',
                'restock_requests.warehouse_id'
            )
            ->get();
        $warehouses = $this->warehouse->all();
        // info($purchaseOrders);
        // info($distributionData);

        return view('admin.goods-receipts.display', compact('purchaseOrders', 'distributionData', 'warehouses'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
