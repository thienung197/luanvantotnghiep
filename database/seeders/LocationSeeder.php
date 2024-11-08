<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            ["id" => "1", "street_address" => "", "ward" => "Cống Vị", "district" => "Ba Đình", "city" => "Hà Nội", "latitude" => "21.0350399", "longitude" => "105.8101844"],
            ["id" => "2", "street_address" => "", "ward" => "Bình Chiểu", "district" => "Thủ Đức", "city" => "Hồ Chí Minh", "latitude" => "10.8778137", "longitude" => "106.7323493"],
            ["id" => "3", "street_address" => "", "ward" => "Cái Khế", "district" => "Ninh Kiều", "city" => "Cần Thơ", "latitude" => "10.0467671", "longitude" => "105.7848072"],
            ["id" => "4", "street_address" => "", "ward" => "", "district" => "", "city" => "", "latitude" => "", "longitude" => ""],
            ["id" => "5", "street_address" => "", "ward" => "", "district" => "", "city" => "", "latitude" => "", "longitude" => ""],
            ["id" => "6", "street_address" => "", "ward" => "", "district" => "", "city" => "", "latitude" => "", "longitude" => ""],
            ["id" => "7", "street_address" => "", "ward" => "", "district" => "", "city" => "", "latitude" => "", "longitude" => ""],

        ];
    }
}
