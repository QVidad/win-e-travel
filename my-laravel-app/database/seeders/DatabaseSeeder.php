<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@mmsu.edu.ph'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        $this->call([
            UserSeeder::class,
            CourseModuleSeeder::class,
            TownSeeder::class,
            DestinationSeeder::class,
            AchievementSeeder::class,
            ContentSectionSeeder::class,
        ]);
    }
}
