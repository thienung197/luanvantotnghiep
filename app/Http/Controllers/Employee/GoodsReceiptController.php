<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\GoodsReceipt;
use App\Models\GoodsReceiptDetail;
use App\Models\Inventory;
use App\Models\Provider;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoodsReceiptController extends Controller
{
    protected $goodsReceipt;
    protected $goodsReceiptDetail;
    protected $batch;
    protected $warehouse;
    protected $provider;
    protected $inventory;
    protected $user;
    public function __construct(
        GoodsReceipt $goodsReceipt,
        Warehouse $warehouse,
        Provider $provider,
        GoodsReceiptDetail $goodsReceiptDetail,
        Batch $batch,
        Inventory $inventory,
        User $user
    ) {
        $this->goodsReceipt = $goodsReceipt;
        $this->goodsReceiptDetail = $goodsReceiptDetail;
        $this->batch = $batch;
        $this->warehouse = $warehouse;
        $this->provider = $provider;
        $this->inventory = $inventory;
        $this->user = $user;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $warehouseId = $user->warehouse_id;
        $goodsReceipts = GoodsReceipt::where('warehouse_id', $warehouseId)->get();
        return view('employee.goods-receipts.index', compact('goodsReceipts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $warehouses = $this->warehouse->all();
        $providers = $this->provider->all();
        $creators = $this->user->all();
        $user = auth()->user();
        $warehouseId = auth()->user()->warehouse_id;
        $warehouse = $this->warehouse->findOrFail($warehouseId);
        $warehouseName = $warehouse->name;
        $lastestCode = $this->goodsReceipt::latest('id')->first();
        if ($lastestCode) {
            $lastNumber = (int)substr($lastestCode->code, 2);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $newCode = 'NH' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
        return view('employee.goods-receipts.create', compact(
            'warehouses',
            'providers',
            'creators',
            'newCode',
            'user',
            'warehouseName'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $lastestCode = Batch::latest('id')->first();
        if ($lastestCode) {
            $lastNumber = (int)substr($lastestCode->code, 2);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $newCode = 'LH' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
        $goodsReceipt = $this->goodsReceipt->create([
            'code' => $request->code,
            'warehouse_id' => $request->warehouse_id,
            'creator_id' => $request->creator_id,
            'provider_id' => $request->provider_id,
            'total_discount' => $request->total_discount
        ]);
        foreach ($request->inputs as $input) {
            $this->goodsReceiptDetail->create([
                'goods_receipt_id' => $goodsReceipt->id,
                'product_id' => $input['product_id'],
                'quantity' => $input['quantity'],
                'unit_price' => $input['unit-price'],
                'discount' => $input['discount'],
                'manufacturing_date' => $input['manufacturing_date'] ?? null,
                'expiry_date' => $input['expiry_date'] ?? null
            ]);

            // $product_batch_id = $this->batch->where('product_id', $input['product_id'])->value('id');
            // $product_inventory = $this->inventory->where('batch_id', $product_batch_id)->first();
            // if ($product_inventory) {
            //     $product_inventory->quantity_available += $input['quantity'];
            //     $product_inventory->save();
            // } else {
            $newBatch = $this->batch->create([
                'code' => $newCode,
                'product_id' => $input['product_id'],
                'price' => $input['unit-price'],
                'manufacturing_date' => $input['manufacturing_date'] ?? null,
                'expiry_date' => $input['expiry_date'] ?? null
            ]);
            if ($newBatch && isset($newBatch->id)) {
                $this->inventory->create([
                    'warehouse_id' => $request->warehouse_id,
                    'quantity_available' => $input['quantity'],
                    // 'minimum_stock_level' => 20,
                    'batch_id' => $newBatch->id,
                ]);
                // }
            }
        }


        return to_route("goodsreceipts.index")->with(["message", "Tạo phiếu nhập hàng thành công!"]);
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
