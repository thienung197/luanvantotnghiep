<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GoodsIssue;
use App\Models\GoodsIssueBatch;
use Illuminate\Http\Request;

class AdminGoodsIssueController extends Controller
{
    protected $goods_issue;
    protected $goods_issue_batch;
    public function __construct(GoodsIssue $goodsIssue, GoodsIssueBatch $goodsIssueBatch)
    {
        $this->goods_issue = $goodsIssue;
        $this->goods_issue_batch = $goodsIssueBatch;
    }

    public function index()
    {
        $goodsIssues = $this->goods_issue::with('user')->latest('id')->get();
        return view('admin.goods_issues.index', compact('goodsIssues'));
    }

    public function store(Request $request)
    {
        dd($request->all());
        // Get the goods-issue-detail id (this could be the product detail ID)
        $goodsIssueId = $request->input('goods-issue');

        // Get the batch data
        $batchData = $request->input('batchData');

        // Loop through the batch data and save to the database
        foreach ($batchData as $batch) {
            $productId = $batch['product_id']; // Product ID
            $totalQuantityRequired = $batch['total_quantity_required']; // Total quantity required
            $batches = $batch['batches']; // Batches

            foreach ($batches as $batchItem) {
                // Assuming `batch_id`, `warehouse_id`, and `quantity` are available in $batchItem
                $batchId = $batchItem['batch_id'];
                $warehouseId = $batchItem['warehouse_id']; // You may need to pass this value if it's not part of the form
                $quantity = $batchItem['quantity'];

                // Insert into the database
                GoodsIssueBatch::create([
                    'goods_issue_detail_id' => $goodsIssueId,
                    'warehouse_id' => $warehouseId,
                    'batch_id' => $batchId,
                    'quantity' => $quantity,
                ]);
            }
        }

        return response()->json(['message' => 'Data saved successfully.']);
    }
}
