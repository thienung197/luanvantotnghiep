<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $warehouses = [
            ["id" => 1, "name" => "Nhà kho Cầu Giấy - Hà Nội", "capacity" => 50000, "size" => "500", "isRefrigerated" => "1", "location_id" => "1"],
            ["id" => 2, "name" => "Nhà kho Quận 1 - Hồ Chí Minh", "capacity" => 55000, "size" => "550", "isRefrigerated" => "1", "location_id" => "2"],
            ["id" => 3, "name" => "Nhà kho Ninh Kiều - Cần Thơ", "capacity" => 45000, "size" => "550", "isRefrigerated" => "1", "location_id" => "3"],
        ];

        foreach ($warehouses as $warehouse) {
            Warehouse::create($warehouse);
        }
    }
}
