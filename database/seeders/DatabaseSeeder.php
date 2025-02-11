<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Category;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call([
            // RoleDatabaseSeeder::class,
            // UserDatabaseSeeder::class,
            // UnitDatabaseSeeder::class,
            CategorySeeder::class,
            WarehouseSeeder::class,
            ProviderSeeder::class,
            LocationSeeder::class,
        ]);
    }
}
