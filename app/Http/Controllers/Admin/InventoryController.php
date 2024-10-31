<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    protected $inventory;
    protected $warehouse;
    protected $batch;
    protected $product;
    public function __construct(Inventory $inventory, Warehouse $warehouse)
    {
        $this->inventory = $inventory;
        $this->warehouse = $warehouse;
    }

    public function index()
    {
        $warehouses = Warehouse::all();
        return view('admin.inventories.index', compact('warehouses'));
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
