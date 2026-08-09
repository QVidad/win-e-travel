<?php

namespace Database\Seeders;

use App\Models\ContentSection;
use Illuminate\Database\Seeder;

class ContentSectionSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            [
                'page_key' => 'home',
                'section_key' => 'hero',
                'title' => 'Master Tour Guiding. Explore Ilocos Norte.',
                'subtitle' => 'WIN e-Travel Training bridges the gap between classroom theory and real-world tour guiding. Practice your delivery, explore 21 municipalities, and develop skills to become a confident, industry-ready tour guide.',
                'content' => [
                    'badge' => 'Interactive Computer-Based Simulation',
                    'primary_btn' => 'Begin Training',
                    'secondary_btn' => 'View Learning Path',
                    'stats' => [
                        ['number' => '21', 'label' => 'Municipalities Covered'],
                        ['number' => '100%', 'label' => 'Interactive Simulation'],
                        ['number' => '3-Tier', 'label' => 'Structured Learning'],
                    ],
                ],
                'is_visible' => true,
            ],
            [
                'page_key' => 'home',
                'section_key' => 'features',
                'title' => 'Key Features of WIN e-Travel Training',
                'subtitle' => 'Designed specifically for Tourism and Hospitality Management students at Mariano Marcos State University.',
                'content' => [
                    'cards' => [
                        [
                            'icon' => 'fas fa-gamepad',
                            'title' => 'Interactive Scenarios',
                            'desc' => 'Engage in realistic dialogue simulations with virtual tourists, facing real-time tour guiding challenges.',
                        ],
                        [
                            'icon' => 'fas fa-map-marked-alt',
                            'title' => 'Complete Coverage',
                            'desc' => 'Explore all 21 municipalities of Ilocos Norte, learning local history, culture, and key tourist attractions.',
                        ],
                        [
                            'icon' => 'fas fa-award',
                            'title' => 'Gamified Progress',
                            'desc' => 'Earn XP, unlock achievement badges, and track your journey from trainee to certified tour guide.',
                        ],
                    ],
                ],
                'is_visible' => true,
            ],
            [
                'page_key' => 'foundation',
                'section_key' => 'overview',
                'title' => 'Foundation Module: Principles of Tour Guiding',
                'subtitle' => 'Essential knowledge, ethical standards, and effective communication techniques for professional tour guides.',
                'content' => [
                    'modules' => [
                        [
                            'id' => 1,
                            'title' => 'Role and Responsibilities of a Professional Tour Guide',
                            'topics' => [
                                'Defining the Tour Guide: Ambassador, Educator, and Leader',
                                'Duty of Care & Safety Protocol Management',
                                'Time Management and Itinerary Execution',
                            ],
                        ],
                        [
                            'id' => 2,
                            'title' => 'Code of Ethics and Professional Conduct',
                            'topics' => [
                                'Respecting Cultural Heritage and Indigenous Customs',
                                'Environmental Stewardship and Sustainable Tourism',
                                'Honesty, Transparency, and Gratuity Ethics',
                            ],
                        ],
                        [
                            'id' => 3,
                            'title' => 'Public Speaking & Commentary Delivery',
                            'topics' => [
                                'Voice Modulation, Clarity, and Pacing',
                                'Storytelling Techniques: Turning Facts into Engaging Tales',
                                'Handling Difficult Questions and Group Management',
                            ],
                        ],
                    ],
                ],
                'is_visible' => true,
            ],
        ];

        foreach ($sections as $sec) {
            ContentSection::updateOrCreate(
                ['page_key' => $sec['page_key'], 'section_key' => $sec['section_key']],
                $sec
            );
        }
    }
}
