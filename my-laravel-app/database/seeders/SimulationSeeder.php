<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SimulationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find Laoag City to link the town simulation
        $laoag = \App\Models\Town::where('slug', 'laoag-city')->first();

        if ($laoag) {
            \App\Models\Simulation::create([
                'type' => 'town',
                'town_id' => $laoag->id,
                'title' => 'Laoag City Tour Guiding Assessment',
                'description' => 'A practical simulation for tour guides specifically for Laoag City.',
                'status' => 'published',
                'scenarios' => [
                    [
                        'location' => 'St. William Cathedral & Sinking Bell Tower',
                        'title' => 'Arrival in Laoag City Center',
                        'prompt' => 'Your tour group steps out in front of the Sinking Bell Tower in Laoag City. A tourist asks: "Why is this bell tower located so far away from the main church structure?" How do you respond?',
                        'image' => '/assets/images/Laoag.jpg',
                        'keywords' => ['Earthquake Baroque', 'Augustinian', 'Sandy', '85 Meters'],
                        'options' => [
                            [
                                'text' => 'Explain that Augustinian friars built it 85 meters away due to earthquake precautions and sandy ground foundation conditions.',
                                'score' => 10,
                                'feedback' => 'Excellent response! Accurate historical context regarding earthquake baroque design.',
                                'isGood' => true,
                            ],
                            [
                                'text' => 'Tell them it was accidentally built in the wrong location by Spanish architects.',
                                'score' => -10,
                                'feedback' => 'Incorrect. The placement was deliberate due to soil and structural stability considerations.',
                                'isGood' => false,
                            ],
                        ],
                    ]
                ]
            ]);
        }

        \App\Models\Simulation::create([
            'type' => 'final',
            'town_id' => null,
            'title' => 'Ilocos Norte Provincial Tour (Grand Finale)',
            'description' => 'The ultimate simulation testing your knowledge across the entire province.',
            'status' => 'published',
            'scenarios' => [
                [
                    'location' => 'Paoay UNESCO World Heritage Church',
                    'title' => 'Guiding at Paoay Church',
                    'prompt' => 'As you approach Paoay Church, guests are amazed by the thick buttresses. What key feature should you highlight in your spoken commentary?',
                    'image' => '/assets/images/Paoay.jpg',
                    'keywords' => ['24 Buttresses', 'UNESCO', 'Coral Stone', 'Sugar Cane'],
                    'options' => [
                        [
                            'text' => 'Highlight the 24 massive coral stone buttresses built to withstand severe seismic activity, inscribing it into UNESCO World Heritage list.',
                            'score' => 10,
                            'feedback' => 'Outstanding commentary! Guests are impressed by your heritage knowledge.',
                            'isGood' => true,
                        ],
                        [
                            'text' => 'Focus only on taking photos and skip explaining the architectural history.',
                            'score' => -5,
                            'feedback' => 'Tourists appreciate photo opportunities, but expected historical commentary.',
                            'isGood' => false,
                        ],
                    ],
                ]
            ]
        ]);
    }
}
