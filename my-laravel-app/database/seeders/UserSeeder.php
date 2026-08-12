<?php

namespace Database\Seeders;

use App\Models\CourseModule;
use App\Models\ModuleProgress;
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

        $student1 = User::updateOrCreate(
            ['email' => 'student@winetravel.com'],
            [
                'name' => 'Juan Dela Cruz',
                'password' => Hash::make('password123'),
                'role' => 'student',
                'status' => 'active',
                'avatar' => '/assets/images/facilitator-male.jpg',
            ]
        );
        
        $student2 = User::updateOrCreate(
            ['email' => 'maria@winetravel.com'],
            [
                'name' => 'Maria Clara',
                'password' => Hash::make('password123'),
                'role' => 'student',
                'status' => 'active',
                'avatar' => '/assets/images/facilitator-female.jpg',
            ]
        );

        $student3 = User::updateOrCreate(
            ['email' => 'jose@winetravel.com'],
            [
                'name' => 'Jose Rizal',
                'password' => Hash::make('password123'),
                'role' => 'student',
                'status' => 'active',
                'avatar' => '/assets/images/facilitator-male.jpg',
            ]
        );

        // Seed some module progress for analytics
        $modules = CourseModule::where('status', 'published')->get();
        if ($modules->count() > 0) {
            // Student 1: Completes everything with high scores
            foreach ($modules as $module) {
                ModuleProgress::updateOrCreate(
                    ['user_id' => $student1->id, 'course_module_id' => $module->id],
                    [
                        'score_percentage' => rand(90, 100),
                        'passed' => true,
                        'unlocked' => true,
                    ]
                );
            }

            // Student 2: Completes half with average scores
            foreach ($modules->take((int)($modules->count() / 2)) as $module) {
                ModuleProgress::updateOrCreate(
                    ['user_id' => $student2->id, 'course_module_id' => $module->id],
                    [
                        'score_percentage' => rand(75, 89),
                        'passed' => false,
                        'unlocked' => true,
                    ]
                );
            }

            // Student 3: Struggles
            foreach ($modules->take(3) as $module) {
                ModuleProgress::updateOrCreate(
                    ['user_id' => $student3->id, 'course_module_id' => $module->id],
                    [
                        'score_percentage' => rand(50, 70),
                        'passed' => false,
                        'unlocked' => true,
                    ]
                );
            }
        }
    }
}
