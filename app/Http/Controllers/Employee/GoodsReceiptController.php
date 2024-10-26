<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\GoodsReceipt;
use App\Models\Provider;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class GoodsReceiptController extends Controller
{
    protected $goodsReceipt;
    protected $warehouse;
    protected $provider;
    public function __construct(GoodsReceipt $goodsReceipt, Warehouse $warehouse, Provider $provider)
    {
        $this->goodsReceipt = $goodsReceipt;
        $this->warehouse = $warehouse;
        $this->provider = $provider;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $goodsReceipts = $this->goodsReceipt->all();
        return view('employee.goods-receipts.index', compact('goodsReceipts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $warehouses = $this->warehouse->all();
        $providers = $this->provider->all();
        return view('employee.goods-receipts.create', compact('warehouses', 'providers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        dd($request->inputs);
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
