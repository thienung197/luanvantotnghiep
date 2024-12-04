<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UpdateInformationController extends Controller
{
    protected $user;
    protected $role;
    protected $location;

    public function __construct(User $user, Role $role, Location $location,)
    {
        $this->user = $user;
        $this->role = $role;
        $this->location = $location;
    }
    public function index()
    {
        $roles = $this->role->all();
        $user = Auth::user();
        $location = \App\Models\Location::find($user->location_id);

        $addressParts = [];

        if ($location) {
            if ($location->street_address) {
                $addressParts[] = $location->street_address;
            }

            if ($location->ward) {
                $addressParts[] = $location->ward;
            }

            if ($location->district) {
                $addressParts[] = $location->district;
            }

            if ($location->city) {
                $addressParts[] = $location->city;
            }
        }

        $fullAddress = implode(', ', $addressParts);
        return view('customers.update.index', compact('roles', 'user', 'fullAddress'));
    }

    public function update(Request $request)
    {
        $user = $this->user->findOrFail($request->user_id);
        $locationData = [];

        if ($request->street_address) {
            $locationData['street_address'] = $request->street_address;
        }
        if ($request->ward) {
            $locationData['ward'] = $request->ward;
        }
        if ($request->district) {
            $locationData['district'] = $request->district;
        }
        if ($request->province) {
            $locationData['city'] = $request->province;
        }
        if ($request->latitude) {
            $locationData['latitude'] = $request->latitude;
        }
        if ($request->longitude) {
            $locationData['longitude'] = $request->longitude;
        }

        if ($locationData) {
            $user->update(['location_id' => null]);
            $this->location->destroy($user->location_id);
        }

        if (!empty($locationData)) {
            $location = $this->location->create($locationData);
            $locationId = $location->id;
        } else {
            $locationId = $user->location_id;
        }

        $dataUpdate = [
            'name' => $request->name,
            'gender' => $request->gender,
            'birth_date' => $request->birth_date,
            'phone' => $request->phone,
            'email' => $request->email,
            'status' => 'active',
            'location_id' => $locationId,
            'warehouse_id' => $request->warehouse_id,
            'image' => $this->user->saveImage($request)
        ];

        if (!empty($request->password)) {
            $dataUpdate['password'] = Hash::make($request->password);
        }

        if ($user) {
            $user->update($dataUpdate);

            if ($dataUpdate['image']) {
                $user->images()->create(['url' => $dataUpdate['image']]);
            }

            return to_route('customers.update.index')->with(['message' => 'Cập nhật thông tin thành công!']);
        } else {
            return back()->withErrors(['user' => 'Không tìm thấy người dùng.']);
        }
    }
}
