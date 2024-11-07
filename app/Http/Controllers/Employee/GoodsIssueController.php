<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Customer;
use App\Models\GoodsIssue;
use App\Models\GoodsIssueBatch;
use App\Models\GoodsIssueDetail;
use App\Models\Inventory;
use App\Models\Provider;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoodsIssueController extends Controller
{
    protected $goodsIssue;
    protected $goodsIssueDetail;
    protected $goodsIssueBatch;
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
        User $user,
        GoodsIssueBatch $goodsIssueBatch
    ) {
        $this->goodsIssue = $goodsIssue;
        $this->goodsIssueDetail = $goodsIssueDetail;
        $this->batch = $batch;
        $this->warehouse = $warehouse;
        $this->customer = $customer;
        $this->inventory = $inventory;
        $this->user = $user;
        $this->goodsIssueBatch = $goodsIssueBatch;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $goodsIssues = $this->goodsIssue::with('user')->latest('id')->get();
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
        $user = Auth::user();
        $locationId = $user->location ? $user->location->id : null;
        $latestCode = GoodsIssue::orderByDesc('id')->first();
        info($latestCode);
        if ($latestCode) {
            $lastNumber = (int)substr($latestCode->code, 2);
            info($lastNumber);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $newCode = 'DH' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
        return view('employee.goods-issues.create', compact(
            'warehouses',
            'customers',
            'creators',
            'locationId',
            'newCode',
            'user'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Tạo mới Goods Issue
        $goodsIssue = $this->goodsIssue->create([
            'code' => $request->code,
            'customer_id' => $request->customer_id,
        ]);

        // Duyệt qua từng product_id trong batchData
        foreach ($request->batchData as $productId => $batchData) {
            // Tìm input cho product_id này trong inputs
            $input = collect($request->inputs)->firstWhere('product_id', $productId);

            if ($input) {
                // Tạo Goods Issue Detail cho từng product_id
                $goodsIssueDetail = $this->goodsIssueDetail->create([
                    'goods_issue_id' => $goodsIssue->id,
                    'product_id' => $productId,
                    'quantity' => $batchData['total_quantity_required'],
                    'unit_price' => $input['unit-price'] ?? 0, // Lấy giá từ inputs
                    'discount' => $input['discount'] ?? 0, // Lấy discount từ inputs nếu có
                ]);

                // Duyệt qua các batches của product_id để tạo Goods Issue Batch
                foreach ($batchData['batches'] as $batch) {
                    $this->goodsIssueBatch->create([
                        'goods_issue_detail_id' => $goodsIssueDetail->id,
                        'batch_id' => $batch['batch_id'],
                        'quantity' => $batch['quantity'],
                        'warehouse_id' => $batch['warehouse_id'],
                    ]);
                }
            }
        }

        return to_route("goodsissues.index")->with("message", "Đơn hàng được đặt thành công!");
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
