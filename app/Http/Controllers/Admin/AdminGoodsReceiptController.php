<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GoodsReceipt;
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
            $query->whereHas('restockRequest', function ($query) {
                $query->where('status', 'approved');
            });
        })->select('id', 'code', 'name', 'provider_id', 'unit_id')
            ->with(['provider:id,name', 'unit:id,name'])
            ->get()
            ->groupBy('provider_id')
            ->map(function ($products, $key) {
                $providerName = $products->first()->provider->name ?? 'Không có dữ liệu tên';
                $providerId = $products->first()->provider->id;
                $productIds = $products->pluck('id')->toArray();
                return [
                    'provider_name' => $providerName,
                    'provider_id' => $providerId,
                    'product_ids' => $productIds,
                    'products' => $products->map(function ($product) {
                        return [
                            'id' => $product->id,
                            'code' => $product->code,
                            'name' => $product->name,
                            'unit' => $product->unit->name ?? 'Không có dữ liệu tên',
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
        $provider = $this->provider->findOrFail($request->provider_id);
        $product_ids = $request->product_ids;
        $approvedProduct = $this->product
            ->whereIn('id', $product_ids)
            ->whereHas('restockRequestDetails.restockRequest', function ($query) {
                $query->where('status', 'approved');
            })
            ->select('id', 'code', 'name', 'unit_id')
            ->with(['unit:id,name', 'restockRequestDetails'])->get();
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
        // dd($request->all ));
        DB::beginTransaction();
        $user_id = $request->creator_id;
        $provider_id = $request->provider_id;
        $code = $request->code;
        $products = $request->products;
        $purchase_order = $this->purchaseOrder->create([
            'code' => $code,
            'provider_id' => $provider_id,
            'user_id' => $user_id
        ]);

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
            }
        }

        DB::commit();
        return to_route('goodsreceipts.index')->with(['message' => 'Tạo phiếu mua hàng thành công!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
