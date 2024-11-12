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
        return view('customers.update.index', compact('roles', 'user'));
    }

    public function update(Request $request)
    {
        // dd($request->all());
        $user = $this->user->findOrFail($request->user_id);
        $locationData = [];

        // Chỉ thêm địa chỉ nếu có giá trị từ request
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

        // Chỉ tạo mới địa điểm nếu có ít nhất một trường được cung cấp
        if (!empty($locationData)) {
            $location = $this->location->create($locationData);
            $locationId = $location->id;
        } else {
            // Nếu không có thông tin location mới, có thể xử lý logic khác như giữ nguyên thông tin cũ hoặc không cập nhật gì cả
            $locationId = $user->location_id; // Lấy ID địa điểm hiện tại
        }

        // Cập nhật thông tin khách hàng
        $dataUpdate = [
            'name' => $request->name,
            'gender' => $request->gender,
            'birth_date' => $request->birth_date,
            'phone' => $request->phone,
            'email' => $request->email,
            'status' => 'active', // Nếu bạn muốn giữ nguyên trạng thái hoặc có thể thay đổi
            'location_id' => $locationId,
            'warehouse_id' => $request->warehouse_id,
            // Không cần mật khẩu nếu không thay đổi
            'image' => $this->user->saveImage($request) // Nếu hình ảnh không cần cập nhật, có thể kiểm tra trước
        ];


        if ($user) {
            $user->update($dataUpdate); // Cập nhật thông tin người dùng

            // Nếu hình ảnh được cập nhật, tạo mới liên kết hình ảnh
            if ($dataUpdate['image']) {
                $user->images()->create(['url' => $dataUpdate['image']]);
            }

            return to_route('customer.dashboard')->with(['message' => 'Cập nhật thông tin khách hàng thành công!']);
        } else {
            return back()->withErrors(['user' => 'Không tìm thấy người dùng.']);
        }
    }
}
