<?php

namespace Database\Seeders;

use App\Models\CourseModule;
use App\Models\QuizQuestion;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class CourseModuleSeeder extends Seeder
{
    public function run(): void
    {
        $adminUser = User::where('role', 'admin')->first() ?? User::first();
        $educatorUser = User::whereIn('role', ['educator', 'teacher'])->first() ?? $adminUser;

        // 1. Four Foundation Modules
        $foundationModules = [
            [
                'code' => 'foundation-1',
                'type' => 'foundation',
                'title' => 'Tour Preparation',
                'description' => 'Destination research, itinerary planning, logistics management, and emergency response readiness.',
                'icon' => 'fas fa-clipboard-list',
                'order' => 1,
                'status' => 'published',
                'updated_by' => $educatorUser?->id,
                'last_modified_at' => Carbon::now()->subDays(2),
            ],
            [
                'code' => 'foundation-2',
                'type' => 'foundation',
                'title' => 'Tour Briefings',
                'description' => 'Engaging guest briefings, timing control, cultural sensitivity, humor, and group dynamics.',
                'icon' => 'fas fa-users',
                'order' => 2,
                'status' => 'published',
                'updated_by' => $educatorUser?->id,
                'last_modified_at' => Carbon::now()->subDays(1),
            ],
            [
                'code' => 'foundation-3',
                'type' => 'foundation',
                'title' => 'Tour Information Delivery',
                'description' => 'Commentary techniques, voice modulation, storytelling, historical facts delivery, and engagement.',
                'icon' => 'fas fa-landmark',
                'order' => 3,
                'status' => 'published',
                'updated_by' => $adminUser?->id,
                'last_modified_at' => Carbon::now()->subHours(12),
            ],
            [
                'code' => 'foundation-4',
                'type' => 'foundation',
                'title' => 'Conclude the Tour',
                'description' => 'Debriefing procedures, feedback collection, guest farewells, and continuous improvement metrics.',
                'icon' => 'fas fa-flag-checkered',
                'order' => 4,
                'status' => 'published',
                'updated_by' => $adminUser?->id,
                'last_modified_at' => Carbon::now()->subHours(3),
            ],
        ];

        foreach ($foundationModules as $mod) {
            $module = CourseModule::updateOrCreate(['code' => $mod['code']], $mod);

            // Add sample questions to the pool for each foundation module
            if ($module->questions()->count() === 0) {
                QuizQuestion::create([
                    'course_module_id' => $module->id,
                    'question' => 'What is the primary duty of care of a licensed tour guide during site visits?',
                    'options' => [
                        'Ensuring tourist health, emergency preparedness, and safety protocols.',
                        'Focusing strictly on selling souvenirs to guests.',
                        'Ignoring schedule timing to take extended breaks.',
                        'Allowing guests to wander unsupervised in restricted areas.',
                    ],
                    'correct_answer_index' => 0,
                    'explanation' => 'Tour guide ethics and DOT guidelines prioritize guest health, safety, and emergency response.',
                    'created_by' => $educatorUser?->id,
                ]);

                QuizQuestion::create([
                    'course_module_id' => $module->id,
                    'question' => 'How should a tour guide handle unexpected itinerary disruptions due to severe weather?',
                    'options' => [
                        'Cancel the entire tour immediately without offering alternatives.',
                        'Execute an approved contingency plan and clearly communicate adjustments to guests.',
                        'Blame local authorities publicly.',
                        'Force guests to continue outdoor activities regardless of danger.',
                    ],
                    'correct_answer_index' => 1,
                    'explanation' => 'Professional tour guides maintain pre-planned alternatives and transparent communication.',
                    'created_by' => $educatorUser?->id,
                ]);
            }
        }

        // 2. Twenty-One Town Chapters
        $towns = [
            ['slug' => 'laoag-city', 'name' => 'Laoag City', 'title' => 'The Sunshine City of the North & Capital of Ilocos Norte'],
            ['slug' => 'paoay', 'name' => 'Paoay', 'title' => 'Home of the UNESCO World Heritage Paoay Church'],
            ['slug' => 'pagudpud', 'name' => 'Pagudpud', 'title' => 'The Boracay of the North & Eco-Tourism Haven'],
            ['slug' => 'adams', 'name' => 'Adams', 'title' => 'The Eco-Tourism Paradise in the Mountains'],
            ['slug' => 'bacarra', 'name' => 'Bacarra', 'title' => 'Home of the Beheading Belfry'],
            ['slug' => 'badoc', 'name' => 'Badoc', 'title' => 'Birthplace of Juan Luna & Sanctuary of La Virgen Milagrosa'],
            ['slug' => 'bangui', 'name' => 'Bangui', 'title' => 'Pioneer of Wind Energy in Southeast Asia'],
            ['slug' => 'banna', 'name' => 'Banna', 'title' => 'Rice & Agriculture Hub'],
            ['slug' => 'burgos', 'name' => 'Burgos', 'title' => 'Home of Cape Bojeador Lighthouse & Kapurpurawan Rock Formation'],
            ['slug' => 'carasi', 'name' => 'Carasi', 'title' => 'Nature & River Sanctuary'],
            ['slug' => 'currimao', 'name' => 'Currimao', 'title' => 'Port Town & Coral Rock Formations'],
            ['slug' => 'dingras', 'name' => 'Dingras', 'title' => 'Rice Granary of Ilocos Norte'],
            ['slug' => 'dumalneg', 'name' => 'Dumalneg', 'title' => 'Cultural Ancestral Domain'],
            ['slug' => 'marcos', 'name' => 'Marcos', 'title' => 'Lush Valleys & Eco-Farm Sites'],
            ['slug' => 'nueva-era', 'name' => 'Nueva Era', 'title' => 'Tingguian Heritage & Eco-Cultural Park'],
            ['slug' => 'pasuquin', 'name' => 'Pasuquin', 'title' => 'Salt Capital & Biscotcho Delicacy Hub'],
            ['slug' => 'piddig', 'name' => 'Piddig', 'title' => 'Home of Basi Revolt & Coffee Capital'],
            ['slug' => 'pinili', 'name' => 'Pinili', 'title' => 'Land of Abel Weaving & Magdalena Gamayo'],
            ['slug' => 'san-nicolas', 'name' => 'San Nicolas', 'title' => 'Pottery & Damili Heritage Capital'],
            ['slug' => 'sarrat', 'name' => 'Sarrat', 'title' => 'Birthplace of Ferdinand E. Marcos & Santa Monica Parish'],
            ['slug' => 'vintar', 'name' => 'Vintar', 'title' => 'Eco-Adventure Park & Vintar Dam'],
        ];

        $order = 5;
        foreach ($towns as $t) {
            $mod = CourseModule::updateOrCreate(
                ['code' => 'town-' . $t['slug']],
                [
                    'type' => 'town_chapter',
                    'title' => $t['name'] . ' Chapter',
                    'description' => $t['title'],
                    'icon' => 'fas fa-map-marked-alt',
                    'order' => $order++,
                    'status' => 'published',
                    'updated_by' => $adminUser?->id,
                    'last_modified_at' => Carbon::now()->subHours(rand(1, 48)),
                ]
            );

            if ($mod->questions()->count() === 0) {
                QuizQuestion::create([
                    'course_module_id' => $mod->id,
                    'question' => "What is the primary cultural or historical significance of {$t['name']}?",
                    'options' => [
                        $t['title'],
                        "Industrial heavy manufacturing district of North Luzon.",
                        "Subtropical high-altitude alpine ski resort.",
                        "Deep-sea oceanic research station.",
                    ],
                    'correct_answer_index' => 0,
                    'explanation' => "{$t['name']} is recognized for: {$t['title']}.",
                    'created_by' => $educatorUser?->id,
                ]);
            }
        }
    }
}
