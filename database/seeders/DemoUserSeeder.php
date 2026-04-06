<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Male User
        if (!User::where('email', 'rahul@example.com')->exists()) {
            User::create([
                'full_name' => 'Rahul Sharma',
                'email' => 'rahul@example.com',
                'password' => Hash::make('password123'),
                'gender' => 'male',
                'role' => 'user',
                'email_verified_at' => now(),
            ]);
        }

        // Create Female User
        if (!User::where('email', 'priya@example.com')->exists()) {
            User::create([
                'full_name' => 'Priya Patel',
                'email' => 'priya@example.com',
                'password' => Hash::make('password123'),
                'gender' => 'female',
                'role' => 'user',
                'email_verified_at' => now(),
            ]);
        }
    }
}
