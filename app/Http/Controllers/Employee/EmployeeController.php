<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\GoodsIssue;
use App\Models\GoodsIssueBatch;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    protected $goodsIssue;
    protected $goodsIssueBatch;
    protected $inventory;
    protected $warehouse;
    protected $product;
    protected $batch;
    public function __construct(
        GoodsIssue $goodsIssue,
        GoodsIssueBatch $goodsIssueBatch,
        Inventory $inventory,
        Warehouse $warehouse,
        Product $product,
        Batch $batch
    ) {
        $this->goodsIssue = $goodsIssue;
        $this->goodsIssueBatch = $goodsIssueBatch;
        $this->inventory = $inventory;
        $this->warehouse = $warehouse;
        $this->product = $product;
        $this->batch = $batch;
    }

    public function showOrders()
    {
        $warehouseId = auth()->user()->warehouse_id;

        $goodsIssueBatches = GoodsIssueBatch::with([
            'goodsIssue',
            'batch.product',
            'goodsIssue.customer',
        ])
            ->whereHas('goodsIssue', function ($query) use ($warehouseId) {
                $query->where('warehouse_id', $warehouseId);
            })
            ->orderBy('id', 'desc')
            ->get();

        // Nhóm các goodsIssueBatches theo goods_issue_id
        $groupedGoodsIssues = $goodsIssueBatches->groupBy('goods_issue_id');

        // Tính tổng tiền hàng cho từng đơn hàng
        $totals = [];
        foreach ($groupedGoodsIssues as $goodsIssueId => $batches) {
            $totalAmount = 0;
            foreach ($batches as $batch) {
                $totalAmount += $batch->quantity * $batch->unit_price - $batch->discount;
            }
            $totals[$goodsIssueId] = $totalAmount;
        }

        return view('employee.showOrders', compact('groupedGoodsIssues', 'totals'));
    }

    public function showInventory()
    {
        $warehouseId = auth()->user()->warehouse_id;

        $products = Product::with([
            'batches' => function ($query) use ($warehouseId) {
                $query->whereHas('inventories', function ($query) use ($warehouseId) {
                    $query->where('warehouse_id', $warehouseId)
                        ->where('quantity_available', '>', 0);
                });
            }
        ])->get();
        info($products);
        return view('employee.inventory', compact('products'));
    }

    public function showDetails($id)
    {
        $warehouse = $this->warehouse->findOrFail($id);

        $batchInWarehouse = $this->inventory->where('warehouse_id', $id)->get();
        $batchIds = $batchInWarehouse->pluck('batch_id');

        $batches = Batch::whereIn('id', $batchIds)->get();

        $products = Product::whereIn('id', $batches->pluck('product_id'))
            ->get()
            ->map(function ($product) use ($batches, $batchInWarehouse) {
                $productBatches = $batches->where('product_id', $product->id);
                info($productBatches);
                $quantityAvailable = $batchInWarehouse->whereIn('batch_id', $productBatches->pluck('id'))->sum('quantity_available');

                return [
                    'name' => $product->name,
                    'code' => $product->code,
                    'quantity_available' => $quantityAvailable,
                    'price' =>
                    // $productBatches->first()->price ?? 
                    null,
                ];
            });

        return response()->json([
            'warehouse' => $warehouse,
            'batches' => $batches,
            'products' => $products,
        ]);
    }
}
