<?php

namespace Database\Seeders;

use App\Models\Achievement;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    public function run(): void
    {
        $achievements = [
            [
                'code' => 'first-step',
                'title' => 'First Step to Guiding',
                'description' => 'Complete the registration process and set up your student tour guide profile.',
                'category' => 'foundation',
                'badge_image' => '/assets/images/badges.jpg',
                'required_xp' => 50,
                'order' => 1,
            ],
            [
                'code' => 'foundation-master',
                'title' => 'Guiding Principles Master',
                'description' => 'Score 100% on the Principles of Tour Guiding & Ethics knowledge module.',
                'category' => 'foundation',
                'badge_image' => '/assets/images/badges.jpg',
                'required_xp' => 100,
                'order' => 2,
            ],
            [
                'code' => 'laoag-explorer',
                'title' => 'Sunshine City Navigator',
                'description' => 'Explore all key destinations in Laoag City and complete the guided tour simulation.',
                'category' => 'exploration',
                'badge_image' => '/assets/images/badges.jpg',
                'required_xp' => 150,
                'order' => 3,
            ],
            [
                'code' => 'paoay-expert',
                'title' => 'Baroque Heritage Scholar',
                'description' => 'Master the historical narration for UNESCO World Heritage Paoay Church.',
                'category' => 'exploration',
                'badge_image' => '/assets/images/badges.jpg',
                'required_xp' => 200,
                'order' => 4,
            ],
            [
                'code' => 'simulation-pro',
                'title' => 'Simulation Master',
                'description' => 'Successfully complete the interactive tour guide scenario with a satisfaction score over 90%.',
                'category' => 'simulation',
                'badge_image' => '/assets/images/badges.jpg',
                'required_xp' => 300,
                'order' => 5,
            ],
            [
                'code' => 'master-tour-guide',
                'title' => 'Certified Ilocos Tour Guide',
                'description' => 'Complete all 21 municipality modules and earn the official digital certification badge.',
                'category' => 'mastery',
                'badge_image' => '/assets/images/badges.jpg',
                'required_xp' => 1000,
                'order' => 6,
            ],
        ];

        foreach ($achievements as $ach) {
            Achievement::updateOrCreate(['code' => $ach['code']], $ach);
        }
    }
}
