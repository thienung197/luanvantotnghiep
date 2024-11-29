<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GoodsIssue;
use App\Models\GoodsIssueBatch;
use App\Models\Inventory;
use App\Models\Notification;
use App\Models\Product;
use App\Models\Warehouse;
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
        $user = auth()->user();
        $userId = $user->id;
        $warehouseId = $user->warehouse_id;
        $goodsIssue->status = 'approved';
        $goodsIssue->approved_by = $userId;
        $goodsIssue->save();
        $batchData = $request->input('batchData');
        $latestGoodsIssue = GoodsIssue::where('id', $goodsIssueId)->first();
        $baseCode = $latestGoodsIssue->code ?? 'DH00000';

        $batchSuffix = 'A';
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
                $batchCode = $baseCode . $batchSuffix;
                $warehouseName = Warehouse::where('id', $warehouseId)->value('name');
                GoodsIssueBatch::create([
                    'goods_issue_id' => $goodsIssueId,
                    'code' => $batchCode,
                    'warehouse_id' => $warehouseId,
                    'batch_id' => $batchId,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'discount' => $discount,
                    'status' => 'processing'
                ]);
                $batchSuffix++;

                $inventory = Inventory::where('batch_id', $batchId)->first();
                info($inventory);
                if ($inventory) {
                    if ($inventory->quantity_available >= $quantity) {
                        info($inventory->quantity);
                        info($quantity);
                        $inventory->quantity_available -= $quantity;
                        $inventory->save();
                    } else {
                        return response()->json([
                            'error' => 'Not enough quantity available for batch ID ' . $batchId
                        ], 400);
                    }
                } else {
                    return response()->json([
                        'error' => 'Inventory not found for batch ID ' . $batchId
                    ], 404);
                }
                $totalQuantityInWarehouse = DB::table('inventories')
                    ->join('batches', 'inventories.batch_id', '=', 'batches.id')
                    ->where('batches.product_id', $productId)
                    ->where('inventories.warehouse_id', $warehouseId)
                    ->sum('inventories.quantity_available');

                $product = Product::find($productId);
                if ($product && $totalQuantityInWarehouse < $product->minimum_stock_level) {
                    Notification::create([
                        'message' => "Sản phẩm {$product->code} - {$product->name} tại kho {$warehouseName} còn {$totalQuantityInWarehouse} sản phẩm.Vui lòng nhập thêm hàng!",
                        'warehouse_id' => $warehouseId,
                        'read_status' => 0
                    ]);
                }
            }
        }
        DB::commit();
        return to_route("admin.goodsissues.index")->with("message", "Đơn hàng được phân kho thành công!");
    }

    //     public function store(Request $request)
    // {
    //     // Giả sử bạn lấy thông tin từ form gửi lên
    //     $product = Product::find($request->product_id);
    //     $stockOutQuantity = $request->quantity;

    //     // Cập nhật số lượng tồn kho của sản phẩm
    //     $product->decrement('quantity', $stockOutQuantity);

    //     // Kiểm tra nếu số lượng tồn kho dưới mức stock_level
    //     if ($product->quantity < $product->stock_level) {
    //         // Tạo thông báo cảnh báo
    //         StockAlert::create([
    //             'product_id' => $product->id,
    //             'message' => 'Sản phẩm "' . $product->name . '" hiện tại dưới mức tồn kho tối thiểu.'
    //         ]);
    //     }

    //     return redirect()->back()->with('success', 'Phiếu xuất kho đã được tạo thành công.');
}
