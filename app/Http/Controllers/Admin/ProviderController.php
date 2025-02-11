<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Providers\CreateProviderRequest;
use App\Http\Requests\Providers\UpdateProviderRequest;
use App\Models\Location;
use App\Models\Provider;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    protected $provider;
    protected $location;
    public function __construct(Provider $provider, Location $location)
    {
        $this->provider = $provider;
        $this->location = $location;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $entries = $request->input('entries', 5);
        $search = $request->search;
        $providers = Provider::when($search, function ($query) use ($search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })->latest('id')->paginate($entries);
        return view('admin.providers.index', compact('providers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.providers.create');
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

        $this->provider->create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'status' => 'active',
            'location_id' => $locationId
        ]);


        return redirect()->route('providers.index')->with(['message' => 'Thêm nhà cung cấp thành công!']);
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
        $provider = $this->provider->findOrFail($id);
        return view('admin.providers.edit', compact('provider'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $dataUpdate = $request->all();
        $provider = $this->provider->findOrFail($id);
        $provider->update($dataUpdate);
        return to_route('providers.index')->with(['message' => 'Chỉnh sửa nhà cung cấp thành công!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $provider = $this->provider->findOrFail($id);
        $provider->delete();
        return to_route('providers.index')->with(['message' => 'Chỉnh sửa nhà cung cấp thành công!']);
    }
}
