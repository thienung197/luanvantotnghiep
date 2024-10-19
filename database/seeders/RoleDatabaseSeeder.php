<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'Super admin'],
            ['name' => 'Admin'],
            ['name' => 'Employee'],
            ['name' => 'Manager'],
            ['name' => 'User']
        ];
        foreach ($roles as $role) {
            Role::updateOrCreate($role);
        }

        $permissions = [
            ['name' => 'Tạo người dùng', 'group' => 'Người dùng'],
            ['name' => 'Cập nhật người dùng', 'group' => 'Người dùng'],
            ['name' => 'Xem người dùng', 'group' => 'Người dùng'],
            ['name' => 'Xóa người dùng', 'group' => 'Người dùng'],

            ['name' => 'Tạo nhóm', 'group' => 'Nhóm'],
            ['name' => 'Cập nhật nhóm', 'group' => 'Nhóm'],
            ['name' => 'Xem nhóm', 'group' => 'Nhóm'],
            ['name' => 'Xóa nhóm', 'group' => 'Nhóm'],

            ['name' => 'Tạo danh mục', 'group' => 'Danh mục'],
            ['name' => 'Cập nhật danh mục', 'group' => 'Danh mục'],
            ['name' => 'Xem danh mục', 'group' => 'Danh mục'],
            ['name' => 'Xóa danh mục', 'group' => 'Danh mục'],

            ['name' => 'Tạo sản phẩm', 'group' => 'Sản phẩm'],
            ['name' => 'Cập nhật sản phẩm', 'group' => 'Sản phẩm'],
            ['name' => 'Xem sản phẩm', 'group' => 'Sản phẩm'],
            ['name' => 'Xóa sản phẩm', 'group' => 'Sản phẩm'],

            ['name' => 'Tạo nhà cung cấp', 'group' => 'Nhà cung cấp'],
            ['name' => 'Cập nhật nhà cung cấp', 'group' => 'Nhà cung cấp'],
            ['name' => 'Xem nhà cung cấp', 'group' => 'Nhà cung cấp'],
            ['name' => 'Xóa nhà cung cấp', 'group' => 'Nhà cung cấp'],

            ['name' => 'Tạo phiếu nhập', 'group' => 'Phiếu nhập'],
            ['name' => 'Cập nhật phiếu nhập', 'group' => 'Phiếu nhập'],
            ['name' => 'Xem phiếu nhập', 'group' => 'Phiếu nhập'],
            ['name' => 'Xóa phiếu nhập', 'group' => 'Phiếu nhập'],

            ['name' => 'Tạo phiếu xuất', 'group' => 'Phiếu xuất'],
            ['name' => 'Cập nhật phiếu xuất', 'group' => 'Phiếu xuất'],
            ['name' => 'Xem phiếu xuất', 'group' => 'Phiếu xuất'],
            ['name' => 'Xóa phiếu xuất', 'group' => 'Phiếu xuất'],

            ['name' => 'Tạo khách hàng', 'group' => 'Khách hàng'],
            ['name' => 'Cập nhật khách hàng', 'group' => 'Khách hàng'],
            ['name' => 'Xem khách hàng', 'group' => 'Khách hàng'],
            ['name' => 'Xóa khách hàng', 'group' => 'Khách hàng'],

            ['name' => 'Tạo kho', 'group' => 'Kho'],
            ['name' => 'Cập nhật kho', 'group' => 'Kho'],
            ['name' => 'Xem kho', 'group' => 'Kho'],
            ['name' => 'Xóa kho', 'group' => 'Kho'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate($permission);
        }
    }
}
