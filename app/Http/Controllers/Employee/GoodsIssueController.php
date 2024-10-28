<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Customer;
use App\Models\GoodsIssue;
use App\Models\GoodsIssueDetail;
use App\Models\Inventory;
use App\Models\Provider;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class GoodsIssueController extends Controller
{
    protected $goodsIssue;
    protected $goodsIssueDetail;
    protected $batch;
    protected $warehouse;
    protected $customer;
    protected $inventory;
    public function __construct(
        GoodsIssue $goodsIssue,
        Warehouse $warehouse,
        Customer $customer,
        GoodsIssueDetail $goodsIssueDetail,
        Batch $batch,
        Inventory $inventory
    ) {
        $this->goodsIssue = $goodsIssue;
        $this->goodsIssueDetail = $goodsIssueDetail;
        $this->batch = $batch;
        $this->warehouse = $warehouse;
        $this->customer = $customer;
        $this->inventory = $inventory;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $goodsIssues = $this->goodsIssue->all();
        return view('employee.goods-issues.index', compact('goodsIssues'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $warehouses = $this->warehouse->all();
        $customers = $this->customer->all();
        return view('employee.goods-issues.create', compact('warehouses', 'customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $goodsIssue = $this->goodsIssue->create([
            'code' => $request->code,
            'warehouse_id' => $request->warehouse_id,
            'creator_id' => 1,
            'customer_id' => $request->customer_id,
            'total_discount' => $request->total_discount
        ]);
        foreach ($request->inputs as $input) {
            $this->goodsIssueDetail->create([
                'goods_receipt_id' => $goodsIssue->id,
                'product_id' => $input['product_id'],
                'quantity' => $input['quantity'],
                'unit_price' => $input['unit-price'],
                'discount' => $input['discount'],
                // 'manufacturing_date' => $input['manufacturing_date'] ?? null,
                // 'expiry_date' => $input['expiry_date'] ?? null
            ]);

            // $product_batch_id = $this->batch->where('product_id', $input['product_id'])->value('id');
            // $product_inventory = $this->inventory->where('batch_id', $product_batch_id)->first();
            // if ($product_inventory) {
            //     $product_inventory->quantity_available += $input['quantity'];
            //     $product_inventory->save();
            // } else {
            // $newBatch = $this->batch->create([
            //     'code' => $goodsIssue->id,
            //     'product_id' => $input['product_id'],
            //     'price' => $input['unit-price'],
            //     'manufacturing_date' => $input['manufacturing_date'] ?? null,
            //     'expiry_date' => $input['expiry_date'] ?? null
            // ]);
            // $newBatch=$this->batch->where('')
            // if ($newBatch && isset($newBatch->id)) {
            //     $this->inventory->create([
            //         'warehouse_id' => $request->warehouse_id,
            //         'quantity_available' => $input['quantity'],
            //         // 'minimum_stock_level' => 20,
            //         'batch_id' => $newBatch->id,
            //     ]);
            //     // }
            // }
        }


        return to_route("goodsissues.index")->with(["message", "Tạo phiếu nhập hàng thành công!"]);
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
