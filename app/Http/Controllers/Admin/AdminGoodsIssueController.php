<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GoodsIssue;
use App\Models\GoodsIssueBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        // dd($request->all());
        DB::beginTransaction();
        $goodsIssueId = $request->input('goods-issue');
        $goodsIssue = $this->goods_issue->findOrFail($goodsIssueId);
        $goodsIssue->status = 'approved';
        $goodsIssue->save();
        $batchData = $request->input('batchData');

        foreach ($batchData as $batch) {
            $productId = $batch['product_id'];
            $totalQuantityRequired = $batch['total_quantity_required'];
            $batches = $batch['batches'];
            foreach ($batches as $batchItem) {
                $batchId = $batchItem['batch_id'];
                $warehouseId = $batchItem['warehouse_id'];
                $quantity = $batchItem['quantity'];
                $unitPrice = str_replace(',', '', $batchItem['unit_price']);
                $discount = $batchItem['discount'];
                $lastBatch = GoodsIssueBatch::where('goods_issue_id', $goodsIssueId)
                    ->latest('id')
                    ->first();

                if ($lastBatch && str_starts_with($lastBatch->code, $goodsIssue->code)) {
                    $lastSuffix = substr($lastBatch->code, -1);
                    $codeSuffix = chr(ord($lastSuffix) + 1);
                }

                GoodsIssueBatch::create([
                    'goods_issue_id' => $goodsIssueId,
                    // 'code' => $goodsIssueId,
                    'warehouse_id' => $warehouseId,
                    'batch_id' => $batchId,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'discount' => $discount,
                    'status' => 'processing'
                ]);
            }
        }
        DB::commit();
        return to_route("admin.goodsissues.index")->with("message", "Đơn hàng được phân kho thành công!");
    }
}
