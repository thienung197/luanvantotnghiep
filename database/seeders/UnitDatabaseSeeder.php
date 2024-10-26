<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('units')->insert([
            ["name" => "Kilôgam"],
            ["name" => "Gam"],
            ["name" => "Lít"],
            ["name" => "Mét"],
            ["name" => "Cái"],
            ["name" => 'Hộp'],
            ["name" => "Thùng"],
            ["name" => "Mét vuông"],
        ]);
    }
}
