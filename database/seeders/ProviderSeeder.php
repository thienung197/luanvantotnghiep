<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $providers = [
            ["id" => "1", "name" => "Nhà cung cấp 1", "phone" => "0976548435"],
        ];
    }
}
