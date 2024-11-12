<?php

namespace App\Http\Controllers;

use App\Models\RestockRequest;
use App\Models\RestockRequestReason;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class RestockRequestController extends Controller
{

    protected $warehouse;
    protected $user;
    protected $restockRequestReason;
    public function __construct(Warehouse $warehouse, User $user, RestockRequestReason $restockRequestReason)
    {
        $this->warehouse = $warehouse;
        $this->user = $user;
        $this->restockRequestReason = $restockRequestReason;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('employee.restock-request.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $warehouses = $this->warehouse->all();
        $creators = $this->user->all();
        $user = auth()->user();
        $lastestCode = RestockRequest::latest('id')->first();
        if ($lastestCode) {
            $lastNumber = (int)substr($lastestCode->code, 5);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $restockRequestReasons = $this->restockRequestReason->all();
        $newCode = 'PYCNH' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
        return view('employee.restock-request.create', compact(
            'warehouses',
            'creators',
            'newCode',
            'user',
            'restockRequestReasons'
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
