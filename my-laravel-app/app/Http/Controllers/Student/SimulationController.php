<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Town;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SimulationController extends Controller
{
    public function index(): Response
    {
        /** @var \App\Models\User $user */
        $user = \Illuminate\Support\Facades\Auth::user();
        $towns = \App\Models\Town::where('status', 'published')->orderBy('order')->get();
        $totalTowns = $towns->count();

        $completedTowns = \App\Models\ModuleProgress::where('user_id', $user->id)
            ->whereHas('courseModule', function ($q) {
                $q->where('type', 'town_chapter');
            })
            ->where('passed', true)
            ->count();
            
        $finalSimulation = \App\Models\Simulation::where('type', 'final')->first();

        return Inertia::render('Student/Simulations/Index', [
            'towns' => $towns,
            'completedTowns' => $completedTowns,
            'totalTowns' => $totalTowns,
            'finalSimulationId' => $finalSimulation ? $finalSimulation->id : null,
        ]);
    }

    public function show(string $id)
    {
        $simulation = \App\Models\Simulation::with('town')->findOrFail($id);

        $module = null;
        if ($simulation->type === 'town' && $simulation->town) {
            $module = \App\Models\CourseModule::where('code', 'town-' . $simulation->town->slug)->first();
            if (!$module) {
                $module = \App\Models\CourseModule::where('code', 'like', 'town-' . $simulation->town->slug . '%')->first();
            }
            
            if ($module) {
                $progress = \App\Models\ModuleProgress::where('user_id', \Illuminate\Support\Facades\Auth::id())
                    ->where('course_module_id', $module->id)
                    ->first();
                
                if (!$progress) {
                    return redirect()->route('towns.show', $simulation->town->slug)->with('error', 'You must visit the town chapter first to unlock this simulation.');
                }
            }
        }

        $scenarios = is_string($simulation->scenarios) ? json_decode($simulation->scenarios, true) : $simulation->scenarios;
        if (!is_array($scenarios)) $scenarios = [];

        shuffle($scenarios);

        $processedScenarios = [];
        foreach ($scenarios as $scenario) {
            $lesson = \App\Models\ModuleLesson::find($scenario['lesson_id'] ?? null);
            $keywords = [];
            if (!empty($scenario['keywords'])) {
                if (is_string($scenario['keywords'])) {
                    // Backward compatibility: convert old string format to new array of objects
                    $parsed = array_values(array_filter(array_map('trim', explode(',', $scenario['keywords']))));
                    foreach ($parsed as $word) {
                        $keywords[] = ['word' => $word, 'points' => 10];
                    }
                } else if (is_array($scenario['keywords'])) {
                    $keywords = $scenario['keywords'];
                }
            }

            $image = '/assets/images/INBackground.jpg';
            if ($lesson && $lesson->cover_image) {
                $image = $lesson->cover_image;
            } else if (!$lesson && $module && $module->cover_image) {
                $image = $module->cover_image;
            }

            $processedScenarios[] = [
                'title' => $scenario['title'] ?? 'Attraction',
                'image' => $image,
                'keywords' => $keywords,
                'time_limit' => (int)($scenario['time_limit'] ?? 60),
            ];
        }

        $simulationArray = $simulation->toArray();
        $simulationArray['scenarios'] = $processedScenarios;

        return Inertia::render('Student/Simulation', [
            'simulation' => $simulationArray,
        ]);
    }

    public function finalBoss(): Response
    {
        $simulation = \App\Models\Simulation::where('type', 'final')->firstOrFail();

        return Inertia::render('Student/Simulation', [
            'simulation' => $simulation,
        ]);
    }

    public function validateSpeech(Request $request)
    {
        $validated = $request->validate([
            'transcript' => 'required|string',
            'required_keywords' => 'required|array',
            'required_keywords.*.word' => 'required|string',
            'required_keywords.*.points' => 'required|numeric',
        ]);

        $transcript = strtolower($validated['transcript']);
        $matchedKeywords = [];
        $totalPoints = 0;
        $earnedPoints = 0;

        foreach ($validated['required_keywords'] as $kw) {
            $word = $kw['word'];
            $points = (int)$kw['points'];
            $totalPoints += $points;
            
            if (str_contains($transcript, strtolower($word))) {
                $matchedKeywords[] = $word;
                $earnedPoints += $points;
            }
        }

        $matchCount = count($matchedKeywords);
        $scorePercent = $totalPoints > 0 ? (int) round(($earnedPoints / $totalPoints) * 100) : 100;
        $xpEarned = $earnedPoints;

        // Update authenticated user stats if available
        /** @var \App\Models\User $user */
        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user) {
            $user->increment('xp', $xpEarned);
        }

        return response()->json([
            'success' => true,
            'matched_keywords' => $matchedKeywords,
            'match_count' => $matchCount,
            'score_percent' => $scorePercent,
            'xp_earned' => $xpEarned,
        ]);
    }

    public function complete(Request $request, string $id)
    {
        /** @var \App\Models\User $user */
        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user) {
            $simulation = \App\Models\Simulation::with('town')->findOrFail($id);
            $passed = $request->input('passed', false);

            $record = \Illuminate\Support\Facades\DB::table('simulation_user')
                ->where('user_id', $user->id)
                ->where('simulation_id', $simulation->id)
                ->first();

            if (!$record) {
                \Illuminate\Support\Facades\DB::table('simulation_user')->insert([
                    'user_id' => $user->id,
                    'simulation_id' => $simulation->id,
                    'passed' => $passed,
                    'attempts' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                \Illuminate\Support\Facades\DB::table('simulation_user')
                    ->where('id', $record->id)
                    ->update([
                        'passed' => $record->passed || $passed,
                        'attempts' => $record->attempts + 1,
                        'updated_at' => now(),
                    ]);
            }

            // Only award XP and progress if they just passed for the first time
            if ($passed && (!$record || !$record->passed)) {
                $user->increment('xp', 150);

                if ($simulation->type === 'town' && $simulation->town_id) {
                    $module = \App\Models\CourseModule::where('type', 'town_chapter')
                        ->where('code', 'town-' . $simulation->town->slug)
                        ->first();
                    
                    if ($module) {
                        \App\Models\ModuleProgress::updateOrCreate(
                            ['user_id' => $user->id, 'course_module_id' => $module->id],
                            ['passed' => true, 'status' => 'completed', 'progress' => 100]
                        );
                    }
                }
            }
        }

        return response()->json(['success' => true]);
    }
}
