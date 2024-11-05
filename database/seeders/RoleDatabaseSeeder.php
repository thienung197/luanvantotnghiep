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
            // ['name' => 'Super admin'],
            ['name' => 'Admin'],
            ['name' => 'Manager'],
            ['name' => 'Customer']
        ];
        foreach ($roles as $role) {
            Role::updateOrCreate($role);
        }

        $permissions = [
            ['name' => 'Tạo người dùng', 'code' => 'create-user', 'group' => 'Người dùng'],
            ['name' => 'Cập nhật người dùng', 'code' => 'update-user', 'group' => 'Người dùng'],
            ['name' => 'Xem người dùng', 'code' => 'view-user', 'group' => 'Người dùng'],
            ['name' => 'Xóa người dùng', 'code' => 'delete-user', 'group' => 'Người dùng'],

            ['name' => 'Tạo nhóm', 'code' => 'create-group', 'group' => 'Nhóm'],
            ['name' => 'Cập nhật nhóm', 'code' => 'update-group', 'group' => 'Nhóm'],
            ['name' => 'Xem nhóm', 'code' => 'view-group', 'group' => 'Nhóm'],
            ['name' => 'Xóa nhóm', 'code' => 'delete-group', 'group' => 'Nhóm'],

            ['name' => 'Tạo danh mục', 'code' => 'create-category', 'group' => 'Danh mục'],
            ['name' => 'Cập nhật danh mục', 'code' => 'update-category', 'group' => 'Danh mục'],
            ['name' => 'Xem danh mục', 'code' => 'view-category', 'group' => 'Danh mục'],
            ['name' => 'Xóa danh mục', 'code' => 'delete-category', 'group' => 'Danh mục'],

            ['name' => 'Tạo sản phẩm', 'code' => 'create-product', 'group' => 'Sản phẩm'],
            ['name' => 'Cập nhật sản phẩm', 'code' => 'update-product', 'group' => 'Sản phẩm'],
            ['name' => 'Xem sản phẩm', 'code' => 'view-product', 'group' => 'Sản phẩm'],
            ['name' => 'Xóa sản phẩm', 'code' => 'delete-product', 'group' => 'Sản phẩm'],

            ['name' => 'Tạo nhà cung cấp', 'code' => 'create-provider', 'group' => 'Nhà cung cấp'],
            ['name' => 'Cập nhật nhà cung cấp', 'code' => 'update-provider', 'group' => 'Nhà cung cấp'],
            ['name' => 'Xem nhà cung cấp', 'code' => 'view-provider', 'group' => 'Nhà cung cấp'],
            ['name' => 'Xóa nhà cung cấp', 'code' => 'delete-provider', 'group' => 'Nhà cung cấp'],

            ['name' => 'Tạo phiếu nhập', 'code' => 'create-goods-receipt', 'group' => 'Phiếu nhập'],
            ['name' => 'Cập nhật phiếu nhập', 'code' => 'update-goods-receipt', 'group' => 'Phiếu nhập'],
            ['name' => 'Xem phiếu nhập', 'code' => 'view-goods-receipt', 'group' => 'Phiếu nhập'],
            ['name' => 'Xóa phiếu nhập', 'code' => 'delete-goods-receipt', 'group' => 'Phiếu nhập'],

            ['name' => 'Tạo phiếu xuất', 'code' => 'create-goods-issue', 'group' => 'Phiếu xuất'],
            ['name' => 'Cập nhật phiếu xuất', 'code' => 'update-goods-issue', 'group' => 'Phiếu xuất'],
            ['name' => 'Xem phiếu xuất', 'code' => 'view-goods-issue', 'group' => 'Phiếu xuất'],
            ['name' => 'Xóa phiếu xuất', 'code' => 'delete-goods-issue', 'group' => 'Phiếu xuất'],

            ['name' => 'Tạo khách hàng', 'code' => 'create-customer', 'group' => 'Khách hàng'],
            ['name' => 'Cập nhật khách hàng', 'code' => 'update-customer', 'group' => 'Khách hàng'],
            ['name' => 'Xem khách hàng', 'code' => 'view-customer', 'group' => 'Khách hàng'],
            ['name' => 'Xóa khách hàng', 'code' => 'delete-customer', 'group' => 'Khách hàng'],

            ['name' => 'Tạo kho', 'code' => 'create-warehouse', 'group' => 'Kho'],
            ['name' => 'Cập nhật kho', 'code' => 'update-warehouse', 'group' => 'Kho'],
            ['name' => 'Xem kho', 'code' => 'view-warehouse', 'group' => 'Kho'],
            ['name' => 'Xóa kho', 'code' => 'delete-warehouse', 'group' => 'Kho'],

            ['name' => 'Xem xuất kho', 'code' => 'view-goods-issue-log', 'group' => 'Xuất kho'],
            ['name' => 'Quản lý xuất kho', 'code' => 'manage-goods-issue', 'group' => 'Xuất kho'],

            ['name' => 'Xem hàng tồn kho', 'code' => 'view-inventory', 'group' => 'Hàng tồn kho'],
            ['name' => 'Cập nhật hàng tồn kho', 'code' => 'update-inventory', 'group' => 'Hàng tồn kho'],

            ['name' => 'Tạo thuộc tính', 'code' => 'create-attribute', 'group' => 'Thuộc tính'],
            ['name' => 'Cập nhật thuộc tính', 'code' => 'update-attribute', 'group' => 'Thuộc tính'],
            ['name' => 'Xem thuộc tính', 'code' => 'view-attribute', 'group' => 'Thuộc tính'],
            ['name' => 'Xóa thuộc tính', 'code' => 'delete-attribute', 'group' => 'Thuộc tính'],

            ['name' => 'Tạo lô hàng', 'code' => 'create-batch', 'group' => 'Lô hàng'],
            ['name' => 'Cập nhật lô hàng', 'code' => 'update-batch', 'group' => 'Lô hàng'],
            ['name' => 'Xem lô hàng', 'code' => 'view-batch', 'group' => 'Lô hàng'],
            ['name' => 'Xóa lô hàng', 'code' => 'delete-batch', 'group' => 'Lô hàng'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate($permission);
        }
    }
}
