<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Nguyen Van A',
                'gender' => 'male',
                'birth_date' => '2000-10-02',
                'phone' => '0974658574',
                'address' => 'HCMC',
                'status' => 'active',
                'email' => 'a@gmail.com',
                'email_verified_at' => now(),
                'password' => bcrypt('password123'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'John Doe',
                'gender' => 'male',
                'birth_date' => '1990-01-01',
                'phone' => '1234567890',
                'address' => '123 Main St, Anytown, USA',
                'status' => 'active',
                'email' => 'john.doe@example.com',
                'email_verified_at' => now(),
                'password' => bcrypt('password123'), // You should use a hashed password
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jane Smith',
                'gender' => 'female',
                'birth_date' => '1992-05-15',
                'phone' => '0987654321',
                'address' => '456 Secondary St, Othertown, USA',
                'status' => 'active',
                'email' => 'jane.smith@example.com',
                'email_verified_at' => now(),
                'password' => bcrypt('password123'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
