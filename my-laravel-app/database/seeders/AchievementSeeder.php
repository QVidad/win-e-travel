<?php

namespace Database\Seeders;

use App\Models\Achievement;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    public function run(): void
    {
        $achievements = [
            // Milestone Badges
            [
                'code' => 'scholar',
                'title' => 'Scholar',
                'description' => 'Complete all "Go Beyond Books" foundation modules.',
                'category' => 'foundation',
                'badge_image' => '/assets/images/badges.jpg',
                'required_xp' => 0, // Unlocked via custom logic
                'order' => 1,
            ],
            [
                'code' => 'virtual-guide',
                'title' => 'Virtual Guide',
                'description' => 'Pass your first "Dare to Discover" Town Simulation.',
                'category' => 'simulation',
                'badge_image' => '/assets/images/badges.jpg',
                'required_xp' => 0,
                'order' => 2,
            ],
            [
                'code' => 'master-guide',
                'title' => 'Ilocos Norte Master',
                'description' => 'Pass the "Adventure Awaits" Final Virtual Tour.',
                'category' => 'mastery',
                'badge_image' => '/assets/images/badges.jpg',
                'required_xp' => 0,
                'order' => 3,
            ],
            // Special Challenge Badges
            [
                'code' => 'flawless-execution',
                'title' => 'Flawless Execution',
                'description' => 'Pass the Final Virtual Tour without ever failing a single quiz or simulation on your first attempt.',
                'category' => 'mastery',
                'badge_image' => '/assets/images/badges.jpg',
                'required_xp' => 0,
                'order' => 4,
            ],
            [
                'code' => 'speedrunner',
                'title' => 'Speedrunner',
                'description' => 'Complete all required modules and the Final Virtual Tour within 7 days of your account creation.',
                'category' => 'mastery',
                'badge_image' => '/assets/images/badges.jpg',
                'required_xp' => 0,
                'order' => 5,
            ],
            [
                'code' => 'silver-tongue',
                'title' => 'Silver Tongue',
                'description' => 'Achieve a perfect 100% speech recognition score on a Town Simulation.',
                'category' => 'simulation',
                'badge_image' => '/assets/images/badges.jpg',
                'required_xp' => 0,
                'order' => 6,
            ],
            [
                'code' => 'overachiever',
                'title' => 'Overachiever',
                'description' => 'Earn a perfect 100% score on the Final Virtual Tour Boss.',
                'category' => 'mastery',
                'badge_image' => '/assets/images/badges.jpg',
                'required_xp' => 0,
                'order' => 7,
            ],
            [
                'code' => 'straight-as',
                'title' => 'Straight A\'s',
                'description' => 'Get 100% on every single End-of-Module Evaluation in Go Beyond Books.',
                'category' => 'foundation',
                'badge_image' => '/assets/images/badges.jpg',
                'required_xp' => 0,
                'order' => 8,
            ],
            [
                'code' => 'unbreakable-resolve',
                'title' => 'Unbreakable Resolve',
                'description' => 'Pass a simulation after failing it at least 2 times.',
                'category' => 'simulation',
                'badge_image' => '/assets/images/badges.jpg',
                'required_xp' => 0,
                'order' => 9,
            ],
        ];

        foreach ($achievements as $ach) {
            Achievement::updateOrCreate(['code' => $ach['code']], $ach);
        }
    }
}
