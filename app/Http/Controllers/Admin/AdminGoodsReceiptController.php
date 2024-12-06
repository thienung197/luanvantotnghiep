<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\GoodsIssue;
use App\Models\GoodsReceipt;
use App\Models\GoodsReceiptDetail;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Provider;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\ReceivingDetail;
use App\Models\ReceivingNotes;
use App\Models\RestockRequest;
use App\Models\RestockRequestDetail;
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
                                'product_id' => $query->product_id,
                                'restock_request_detail_id' => $query->id
                            ];
                        });
                        $totalQuantity = $restockDetails->sum('quantity');
                        $restockRequestDetailIds = $restockDetails->pluck('restock_request_detail_id')->toArray();
                        return [
                            'id' => $product->id,
                            'code' => $product->code,
                            'name' => $product->name,
                            'unit' => $product->unit->name,
                            'restock_details' => $restockDetails,
                            'totalQuantity' => $totalQuantity,
                            'restock_request_detail_ids' => $restockRequestDetailIds
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
        // dd($request->all());
        DB::beginTransaction();
        $user_id = $request->creator_id;
        $provider_id = $request->provider_id;
        $code = $request->code;
        $products = $request->products;
        $purchase_order = $this->purchaseOrder->create([
            'code' => $code,
            'provider_id' => $provider_id,
            'user_id' => $user_id,
            'status' => 'pending'
        ]);
        foreach ($products as $productId => $productData) {
            $product = $this->product->findOrFail($productId);
            if ($product) {
                $this->purchaseOrderDetail->create([
                    'purchase_order_id' => $purchase_order->id,
                    'product_id' => $product->id,
                    'quantity' => $productData['quantity'],
                ]);
                $orderedProducIds[] = $product->id;
            }
            $restockRequestDetailIds = explode(',', $productData['restock_request_detail_ids']);
            DB::table('restock_request_details')->whereIn('id', $restockRequestDetailIds)->update(['status' => 'ordered']);
        }


        DB::commit();
        return to_route('goodsreceipts.index')->with(['message' => 'Tạo phiếu đặt hàng thành công!']);
    }

    public function storeReceipt(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();

        $lastGoodsReceipt = GoodsReceipt::latest('id')->first();
        $lastCode = $lastGoodsReceipt ? $lastGoodsReceipt->code : 'PNH0000';
        $nextCodeNumber = intval(substr($lastCode, 3)) + 1;

        $lastReceive = ReceivingNotes::latest('id')->first();
        $lastCodeReceive = $lastReceive ? $lastReceive->code : 'PGN0000';
        $nextCodeReceiveNumber = intval(substr($lastCodeReceive, 3)) + 1;
        $receive = ReceivingNotes::create([
            'purchase_order_id' => $request->purchase_order,
            'provider_id' => $request->provider_id,
            'user_id' => $request->user_id,
        ]);
        $receiveDetail = ReceivingDetail::create([
            'receiving_notes_id' => $receive->id,
            'product_id' => $request->product_id,
            'quantity' => $request->delivered_quantity,
            'unit_price' => $request->unit_price,
            'discount' => $request->discount,
            'manufacturing_date' => $request->nsx,
            'expiry_date' => $request->hsd
        ]);


        foreach ($request->distributions as $distribution) {

            $newCode = 'PNH' . str_pad($nextCodeNumber, 4, '0', STR_PAD_LEFT);
            $nextCodeNumber++;

            $goodsReceipt = GoodsReceipt::create([
                'code' => $newCode,
                'provider_id' => $request->provider_id,
                'creator_id' => $request->user_id,
                'warehouse_id' => $distribution['warehouse_id'],
                'receiving_detail_id' => $receiveDetail->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            PurchaseOrderDetail::where('purchase_order_id', $request->purchase_order)
                ->where('product_id', $distribution['product_id'])->update(['status' => 'fulfilled']);
            //cap nhat purchase order
            $purchaseOrderId = $request->purchase_order;
            $orderDetails = PurchaseOrderDetail::where('purchase_order_id', $purchaseOrderId)->get();
            $totalDetails = $orderDetails->count();
            $pendingDetails = $orderDetails->where('status', 'pending')->count();
            $fulfilledDetails = $orderDetails->where('status', 'fulfilled')->count();
            if ($pendingDetails === $totalDetails) {
                $orderStatus = 'pending';
            } elseif ($fulfilledDetails === $totalDetails) {
                $orderStatus = 'fulfilled';
            } else {
                $orderStatus = 'processing';
            }
            PurchaseOrder::where('id', $purchaseOrderId)
                ->update(['status' => $orderStatus]);

            $warehouseId = $distribution['warehouse_id'];
            RestockRequestDetail::where('status', 'ordered')
                ->where('product_id', $distribution['product_id'])
                ->where('quantity', $distribution['quantity'])
                ->whereHas('restockRequest', function ($query) use ($warehouseId) {
                    $query->where('warehouse_id', $warehouseId);
                })->update(['status' => 'fulfilled']);
            GoodsReceiptDetail::create([
                'goods_receipt_id' => $goodsReceipt->id,
                'product_id' => $distribution['product_id'],
                'quantity' => $distribution['quantity'],
                'unit_price' => $request->unit_price,
                'discount' => $request->discount,
                'manufacturing_date' => $request->nsx,
                'expiry_date' => $request->hsd,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $newCodeReceive = 'PGN' . str_pad($nextCodeReceiveNumber, 4, '0', STR_PAD_LEFT);
            $nextCodeReceiveNumber++;


            $latestCode = Batch::orderByDesc('id')->first();
            if ($latestCode) {
                $lastNumber = (int)substr($latestCode->code, 2);
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }
            $newCode = 'LH' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
            $batch = Batch::create([
                'code' => $newCode,
                'product_id' => $distribution['product_id'],
                'price' => $request->unit_price,
                'manufacturing_date' => $request->nsx,
                'expiry_date' => $request->hsd,
            ]);
            $batchId = $batch->id;
            $inventory = Inventory::where('batch_id', $batchId)->first();
            info($inventory);
            if ($inventory) {
                info($inventory->quantity);
                info($distribution['quantity']);
                $inventory->quantity_available += $distribution['quantity'];
                $inventory->save();
            } else {
                Inventory::create([
                    'batch_id' => $batchId,
                    'warehouse_id' => $distribution['warehouse_id'],
                    'quantity_available' => $distribution['quantity'],
                ]);
            }
        }

        DB::commit();
        return redirect()->route('goodsreceipts.display')->with('message', 'Phiếu nhận hàng đã được tạo thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show() {}
    public function display()
    {
        // dd(route('goodsreceipts.display'));
        $purchaseOrders = $this->purchaseOrder::with(['purchaseOrderDetails' => function ($query) {
            $query->where('status', 'pending')->with(['product' => function ($query) {
                $query->select('id', 'code', 'name', 'unit_id')->with('unit:id,name');
            }]);
        }, 'provider:id,name'])->latest('id')->get();
        $recordedProducts = ReceivingDetail::join('products', 'receiving_details.product_id', '=', 'products.id')
            ->join('units', 'products.unit_id', '=', 'units.id')
            ->join('receiving_notes', 'receiving_details.receiving_notes_id', '=', 'receiving_notes.id')
            ->join('goods_receipts', 'receiving_details.id', '=', 'goods_receipts.receiving_detail_id')
            ->join('goods_receipt_details', 'goods_receipts.id', '=', 'goods_receipt_details.goods_receipt_id')
            ->join('warehouses', 'goods_receipts.warehouse_id', '=', 'warehouses.id')
            ->join('purchase_orders', 'receiving_notes.purchase_order_id', '=', 'purchase_orders.id')
            ->select(
                'products.code as product_code',
                'products.name as product_name',
                'units.name as unit_name',
                'receiving_details.quantity as received_quantity',
                'receiving_details.manufacturing_date as nsx',
                'receiving_details.expiry_date as hsd',
                'receiving_details.unit_price as unit_price',
                'receiving_details.discount as discount',
                'warehouses.name as warehouse_name',
                'goods_receipt_details.quantity as warehouse_quantity',
                'receiving_notes.purchase_order_id'
            )
            ->get()
            ->groupBy('purchase_order_id');
        info($recordedProducts);
        // $productPendingIds = $purchaseOrders->flatMap(function ($order) {
        //     return $order->purchaseOrderDetails->where('status', 'pending')->pluck('product_id');
        // });

        $requestDetails = RestockRequestDetail::join('restock_requests', 'restock_request_details.restock_request_id', '=', 'restock_requests.id')
            ->select('restock_request_details.*', 'restock_requests.warehouse_id')
            ->where('restock_request_details.status', 'ordered')->get();
        // info($requestDetails);
        $groupedRequestDetails = $requestDetails->groupBy('product_id');
        // info($groupedRequestDetails);
        foreach ($purchaseOrders as $purchaseOrder) {
            $purchaseOrder->recordedProducts = $recordedProducts->get($purchaseOrder->id, collect());
            foreach ($purchaseOrder->purchaseOrderDetails as $detail) {
                $detail->requestDetails = $groupedRequestDetails->get($detail->product_id, collect());
            }
        }
        // info($purchaseOrders);

        // $distributionData = DB::table('restock_request_details')
        //     ->join('restock_requests', 'restock_request_details.restock_request_id', '=', 'restock_requests.id')
        //     ->whereIn('restock_request_details.product_id', $productPendingIds)
        //     ->where('restock_request_details.status', 'ordered')
        //     ->select(
        //         'restock_request_details.product_id',
        //         'restock_request_details.quantity',
        //         'restock_requests.warehouse_id'
        //     )
        //     ->get();
        // info($distributionData);
        $warehouses = $this->warehouse->all();
        // info($purchaseOrders);
        // info($distributionData);

        return view('admin.goods-receipts.display', compact('purchaseOrders',  'warehouses'));
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
