<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ["name" => 'Thực phẩm', "parent_id" => null],
            ["name" => "Đồ uống", "parent_id" => null],
            ["name" => "Gia dụng", "parent_id" => null],
            ["name" => "Điện tử", "parent_id" => null],
            ["name" => "Thời trang", "parent_id" => null],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate($category);
        }
    }
}
