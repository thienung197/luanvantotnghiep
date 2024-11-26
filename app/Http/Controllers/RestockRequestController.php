<?php

namespace App\Http\Controllers;

use App\Models\RestockRequest;
use App\Models\RestockRequestDetail;
use App\Models\RestockRequestReason;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RestockRequestController extends Controller
{

    protected $warehouse;
    protected $user;
    protected $restockRequestReason;
    protected $restockRequest;
    public function __construct(Warehouse $warehouse, User $user, RestockRequestReason $restockRequestReason, RestockRequest $restockRequest)
    {
        $this->warehouse = $warehouse;
        $this->user = $user;
        $this->restockRequestReason = $restockRequestReason;
        $this->restockRequest = $restockRequest;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $warehouseId = $user->warehouse_id;
        $restockRequests = $this->restockRequest::with('warehouse')->where('warehouse_id', $warehouseId)->latest('id')->get();
        return view('employee.restock-request.index', compact('restockRequests'));
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
        // dd($request->all());
        $data = json_decode($request->data, true);
        // DB::beginTransaction();
        $restockRequest = RestockRequest::create([
            'code' => $request->code,
            'user_id' => $data['user_id'],
            'warehouse_id' => $data['warehouse_id'],
            'status' => 'pending',
        ]);
        foreach ($data['products'] as $product) {
            RestockRequestDetail::create([
                'restock_request_id' => $restockRequest->id,
                'product_id' => $product['id'],
                'quantity' => $product['suggested_quantity'],
                'status' => 'pending'
            ]);
        }
        return to_route('restock-request.index')->with(['message' => 'Tạo yêu cầu nhập hàng thành công!']);
        // DB::commit();
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
