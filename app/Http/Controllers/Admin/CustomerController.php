<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\CreateCustomerRequest;
use App\Models\Customer;
use App\Models\Location;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    protected $customer;
    protected $location;
    public function __construct(Customer $customer, Location $location)
    {
        $this->customer = $customer;
        $this->location = $location;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $entries = $request->input('entries', 5);
        $search = $request->search;
        $customers = Customer::when($search, function ($query) use ($search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })->latest("id")->paginate($entries);
        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $location = $this->location->create([
            'street_address' => $request->street_address || null,
            'ward' => $request->ward,
            'district' => $request->district,
            'city' => $request->province,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude
        ]);
        $locationId = $location->id;

        $this->customer->create([
            'name' => $request->name,
            'gender' => $request->gender,
            'phone' => $request->phone,
            'location_id' => $locationId
        ]);
        return to_route('customers.index')->with(['message' => 'Thêm khách hàng thành công!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $customer = $this->customer->findOrFail($id);
        return view('admin.customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $customer = $this->customer->findOrFail($id);
        $dataUpdate = $request->all();
        $customer->update($dataUpdate);
        return to_route('customers.index')->with(['message' => 'Cập nhật khách hàng thành công!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $customer = $this->customer->findOrFail($id);
        $customer->delete();
        return to_route('customers.index')->with(['message' => 'Xóa khách hàng thành công!']);
    }
}
