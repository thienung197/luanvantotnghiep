<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\CreateUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Models\Location;
use App\Models\Role;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected $user;
    protected $role;
    protected $location;
    protected $warehouse;

    public function __construct(User $user, Role $role, Location $location, Warehouse $warehouse)
    {
        $this->user = $user;
        $this->role = $role;
        $this->location = $location;
        $this->warehouse = $warehouse;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $entries = $request->input('entries', 5);
        $search = $request->input('search');
        $users = $this->user::when($search, function ($query) use ($search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })->latest('id')->paginate($entries);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = $this->role->all();
        $warehouses = $this->warehouse->all();
        return view('admin.users.create', compact('roles', 'warehouses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $location = $this->location->create([
            'street_address' => $request->street_address ?? null,
            'ward' => $request->ward,
            'district' => $request->district,
            'city' => $request->province,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude
        ]);

        $locationId = $location->id;

        $dataCreate = [
            'name' => $request->name,
            'gender' => $request->gender,
            'birth_date' => $request->birth_date,
            'phone' => $request->phone,
            'email' => $request->email,
            'status' => 'active',
            'location_id' => $locationId,
            'warehouse_id' => $request->warehouse_id,
            'password' => Hash::make($request->password),
            'image' => $this->user->saveImage($request)
        ];

        $user = $this->user->create($dataCreate);

        if ($dataCreate['image']) {
            $user->images()->create(['url' => $dataCreate['image']]);
        }

        if ($request->has('role_ids') && is_array($request->role_ids)) {
            $user->roles()->attach($request->role_ids);
        }

        return to_route('users.index')->with(['message' => 'Tạo người dùng thành công!']);
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
        $user = User::findOrFail($id)->load('roles');
        $roles = $this->role->all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id)
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
        $dataUpdate = $request->except('password');
        if ($request->has('password')) {
            $dataUpdate['password'] = Hash::make($request->password);
        }
        $user = $this->user->findOrFail($id)->load('roles');
        $currentImage = $user->images->count() > 0 ? $user->images->first()->url : '';
        $dataUpdate['image'] = $this->user->updateImage($request, $currentImage);
        $user->update($dataUpdate);
        $user->images()->delete();
        $user->images()->create(['url' => $dataUpdate['image']]);
        if ($request->has('role_ids')) {
            $user->roles()->sync($dataUpdate['role_ids']);
        }
        return to_route('users.index')->with(['message' => 'Cập nhật người dùng thành công!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = $this->user->findOrFail($id)->load('roles');
        $user->images()->delete();
        $imageName = $user->images->count() > 0 ? $user->images->first()->url : '';
        $this->user->deleteImage($imageName);
        $user->delete();
        return to_route('users.index')->with(['message' => 'Xóa người dùng thành công!']);
    }
}
