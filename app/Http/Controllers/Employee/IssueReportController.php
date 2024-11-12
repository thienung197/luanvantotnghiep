<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\GoodsIssue;
use App\Models\Product;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IssueReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('employee.issue-report.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $warehouses = Warehouse::all();
        // $customers = $this->customer->all();
        $creators = User::all();
        $user = Auth::user();
        $warehouseId = $user->warehouse_id;
        $locationId = $user->location ? $user->location->id : null;
        $latestCode = GoodsIssue::orderByDesc('id')->first();
        if ($latestCode) {
            $lastNumber = (int)substr($latestCode->code, 2);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $newCode = 'DH' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
        $cart = session()->get('cart', []);
        $products = Product::whereIn('id', $cart)->get();
        $customer = auth()->user();
        $customerId = $customer->id;
        return view('employee.issue-report.create', compact(
            'warehouses',
            // 'customers',
            'creators',
            'locationId',
            'newCode',
            'user',
            'products',
            'customerId',
            'warehouseId'
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
