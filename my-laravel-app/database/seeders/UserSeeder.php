<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@winetravel.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'status' => 'active',
                'avatar' => '/assets/images/WINLogo.png',
            ]
        );

        User::updateOrCreate(
            ['email' => 'educator@winetravel.com'],
            [
                'name' => 'Prof. Maria Santos',
                'password' => Hash::make('password123'),
                'role' => 'educator',
                'status' => 'active',
                'avatar' => '/assets/images/facilitator-female.jpg',
            ]
        );

        User::updateOrCreate(
            ['email' => 'student@winetravel.com'],
            [
                'name' => 'Juan Dela Cruz',
                'password' => Hash::make('password123'),
                'role' => 'student',
                'status' => 'active',
                'avatar' => '/assets/images/facilitator-male.jpg',
            ]
        );
    }
}
