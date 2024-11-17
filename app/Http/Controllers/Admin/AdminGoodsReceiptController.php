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
        // DB::beginTransaction();

        try {
            // Lấy mã phiếu nhận hàng cuối cùng và tạo mã mới (mã sẽ tự động tăng)
            $lastGoodsReceipt = GoodsReceipt::latest('id')->first();
            $lastCode = $lastGoodsReceipt ? $lastGoodsReceipt->code : 'PNH0000';
            $nextCodeNumber = intval(substr($lastCode, 3)) + 1;

            // Lặp qua từng phân phối và tạo phiếu nhận hàng tương ứng
            foreach ($request->distributions as $productId => $distributions) {
                // Kiểm tra xem $distributions có phải là mảng không
                if (!is_array($distributions)) {
                    throw new \Exception("Dữ liệu phân phối không hợp lệ.");
                }

                // Tạo mã phiếu nhận hàng cho mỗi phân phối (tăng số tự động)
                $newCode = 'PNH' . str_pad($nextCodeNumber, 4, '0', STR_PAD_LEFT);
                $nextCodeNumber++;

                // Tạo phiếu nhận hàng cho từng kho (mỗi phân phối tạo một phiếu nhận hàng)
                foreach ($distributions as $distribution) {
                    // Kiểm tra nếu distribution là mảng và có đầy đủ dữ liệu
                    if (is_array($distribution) && isset($distribution['warehouse_id'], $distribution['quantity'], $distribution['product_id'], $distribution['unit_price'], $distribution['discount'])) {
                        // Tạo GoodsReceipt mới cho mỗi kho
                        $goodsReceipt = GoodsReceipt::create([
                            'code' => $newCode, // Mã tự động tăng
                            'provider_id' => $request->provider_id,
                            'creator_id' => $request->user_id,  // Lấy ID người tạo từ request
                            'warehouse_id' => $distribution['warehouse_id'],  // Lấy warehouse_id từ phân phối
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        // Lưu chi tiết phiếu nhận hàng cho từng phân phối
                        GoodsReceiptDetail::create([
                            'goods_receipt_id' => $goodsReceipt->id,  // ID phiếu nhận hàng
                            'product_id' => $distribution['product_id'],
                            'warehouse_id' => $distribution['warehouse_id'], // Lấy warehouse_id từ phân phối
                            'quantity' => $distribution['quantity'],
                            'unit_price' => $distribution['unit_price'],
                            'discount' => $distribution['discount'],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    } else {
                        throw new \Exception("Thông tin phân phối không hợp lệ.");
                    }
                }
            }

            // DB::commit();
            return redirect()->route('goodsreceipts.index')->with('success', 'Phiếu nhận hàng đã được tạo thành công.');
        } catch (\Exception $e) {
            DB::rollBack(); // Nếu có lỗi, rollback lại transaction
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
        info($purchaseOrders);
        info($distributionData);

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
