<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ["id" => "1", "name" => 'Thời trang nam', "parent_id" => null],
            ["id" => "2", "name" => "Thời trang nữ", "parent_id" => null],
            ["id" => "3", "name" => "Điện thoại và phụ kiện", "parent_id" => null],
            ["id" => "4", "name" => "Máy tính và Laptop", "parent_id" => null],
            ["id" => "5", "name" => "Thiết bị gia dụng", "parent_id" => null],
            ["id" => "6", "name" => 'Sắc đẹp', "parent_id" => null],
            ["id" => "7", "name" => "Sức khỏe", "parent_id" => null],
            ["id" => "8", "name" => "Phụ kiện và trang sức", "parent_id" => null],
            ["id" => "9", "name" => "Bách hóa", "parent_id" => null],
            ["id" => "10", "name" => "Nhà sách", "parent_id" => null],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
