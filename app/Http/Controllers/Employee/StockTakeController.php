<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Product;
use App\Models\StockTake;
use App\Models\StockTakeDetail;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class StockTakeController extends Controller
{
    protected $user;
    protected $product;
    protected $warehouse;
    protected $stockTake;
    protected $stockTakeDetail;

    public function __construct(User $user, Product $product, Warehouse $warehouse, StockTake $stockTake, StockTakeDetail $stockTakeDetail)
    {
        $this->user = $user;
        $this->product = $product;
        $this->warehouse = $warehouse;
        $this->stockTake = $stockTake;
        $this->stockTakeDetail = $stockTakeDetail;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stockTakes = $this->stockTake->all();
        return view('employee.stock-takes.index', compact('stockTakes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $warehouses = $this->warehouse->all();
        $creators = $this->user->all();
        $lastestCode = StockTake::latest('code')->first();
        if ($lastestCode) {
            $lastNumber = (int)substr($lastestCode->code, 2);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $newCode = 'KK' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
        info($newCode);
        return view('employee.stock-takes.create', compact('warehouses', 'creators', 'newCode'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $stockTakeCreate = $this->stockTake->create([
            'code' => $request->code,
            'user_id' => $request->creator_id,
            'date' => $request->date,
            'warehouse_id' => $request->warehouse_id,
            'notes' => $request->notes
        ]);
        foreach ($request->inputs as $input) {
            $this->stockTakeDetail->create([
                'stock_take_id' => $stockTakeCreate->id,
                'product_id' => $input['product_id'],
                'inventory_quantity' => $input['batch-quantity'],
                'actual_quantity' => $input['actual-quantity'],
                'price' => $input['price'],
            ]);
            $batchId = $input['batch_id'];
            $batch = Batch::findOrFail($batchId);
            foreach ($batch->inventories as $inventory) {
                $inventory->quantity_available = $input['actual-quantity'];
                $inventory->save();
            }
        }


        return to_route("stocktakes.index")->with(["message", "Tạo phiếu kiểm kho thành công!"]);
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
