<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Warehouses\CreateWarehouseRequest;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    protected $warehouse;
    public function __construct(Warehouse $warehouse)
    {
        $this->warehouse = $warehouse;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $entries = $request->input('entries', 5);
        $search = $request->search;
        $warehouses = Warehouse::when($search, function ($query) use ($search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })->latest("id")->paginate($entries);
        return view('admin.warehouses.index', compact('warehouses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.warehouses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateWarehouseRequest $request)
    {
        $dataCreate = $request->all();
        $this->warehouse->create($dataCreate);
        return to_route('warehouses.index')->with(['message' => 'Thêm nhà kho thành công!']);
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
        $warehouse = $this->warehouse->findOrFail($id);
        return view('admin.warehouses.edit', compact('warehouse'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateWarehouseRequest $request, string $id)
    {
        $warehouse = $this->warehouse->findOrFail($id);
        $dataUpdate = $request->all();
        $warehouse->update($dataUpdate);
        return to_route('warehouses.index')->with(['message' => 'Chỉnh sửa nhà kho thành công!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $warehouse = $this->warehouse->findOrFail($id);
        $warehouse->delete();
        return to_route('warehouses.index')->with(['message' => 'Xóa nhà cung cấp thành công!']);
    }
}
