<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user if not exists
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Create salesperson user if not exists
        User::firstOrCreate(
            ['email' => 'sales@example.com'],
            [
                'name' => 'Sales Person',
                'password' => Hash::make('password'),
                'role' => 'salesperson',
            ]
        );
    }
}
