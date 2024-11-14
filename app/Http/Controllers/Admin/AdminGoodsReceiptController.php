<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GoodsReceipt;
use App\Models\Provider;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminGoodsReceiptController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $goodsReceipt;
    protected $warehouse;
    protected $user;
    protected $provider;
    public function __construct(GoodsReceipt $goodsReceipt, Warehouse $warehouse, User $user, Provider $provider)
    {
        $this->goodsReceipt = $goodsReceipt;
        $this->warehouse = $warehouse;
        $this->user = $user;
        $this->provider = $provider;
    }
    public function index()
    {
        $goodsReceipts = $this->goodsReceipt->all();
        return view('admin.goods-receipts.index', compact('goodsReceipts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $warehouses = $this->warehouse->all();
        $providers = $this->provider->all();
        $creators = $this->user->all();
        $lastestCode = $this->goodsReceipt::latest('id')->first();
        if ($lastestCode) {
            $lastNumber = (int)substr($lastestCode->code, 2);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $newCode = 'NH' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
        return view('admin.goods-receipts.create', compact(
            'warehouses',
            'providers',
            'creators',
            'newCode',
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
