<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\CreateUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected $user;
    protected $role;

    public function __construct(User $user, Role $role)
    {
        $this->user = $user;
        $this->role = $role;
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
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateUserRequest $request)
    {
        dd($request->all());
        $dataCreate = $request->all();
        $dataCreate['status'] = 'active';
        $dataCreate['password'] = Hash::make($request->password);
        $dataCreate['image'] = $this->user->saveImage($request);
        $user = $this->user->create($dataCreate);
        $user->images()->create(['url' => $dataCreate['image']]);
        if ($request->has('role_ids')) {
            $user->roles()->attach($dataCreate['role_ids']);
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
        return to_route('users.index')->with(['<mes></mes>sage' => 'Cập nhật người dùng thành công!']);
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
