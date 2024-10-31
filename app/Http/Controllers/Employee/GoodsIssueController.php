<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Customer;
use App\Models\GoodsIssue;
use App\Models\GoodsIssueDetail;
use App\Models\Inventory;
use App\Models\Provider;
use App\Models\User;
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
    protected $user;
    public function __construct(
        GoodsIssue $goodsIssue,
        Warehouse $warehouse,
        Customer $customer,
        GoodsIssueDetail $goodsIssueDetail,
        Batch $batch,
        Inventory $inventory,
        User $user
    ) {
        $this->goodsIssue = $goodsIssue;
        $this->goodsIssueDetail = $goodsIssueDetail;
        $this->batch = $batch;
        $this->warehouse = $warehouse;
        $this->customer = $customer;
        $this->inventory = $inventory;
        $this->user = $user;
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
        $creators = $this->user->all();
        return view('employee.goods-issues.create', compact('warehouses', 'customers', 'creators'));
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
                'goods_issue_id' => $goodsIssue->id,
                'product_id' => $input['product_id'],
                'quantity' => $input['quantity'],
                'unit_price' => $input['unit-price'],
                'discount' => $input['discount'],
                'manufacturing_date' => $input['manufacturing_date'] ?? null,
                'expiry_date' => $input['expiry_date'] ?? null
            ]);
            $batchId = $input['batch_id'];
            $inventoryId = $this->inventory->where('batch_id', $batchId)->first();
            if ($inventoryId && $inventoryId->quantity_available >= $input['quantity']) {
                $inventoryId->quantity_available -= $input['quantity'];
                $inventoryId->save();
            }
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
