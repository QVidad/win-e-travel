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
                    return redirect()->route('dare-to-discover.show', $simulation->town->slug)->with('error', 'You must visit the town chapter first to unlock this simulation.');
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
        $simulation = \App\Models\Simulation::firstOrCreate(
            ['type' => 'final'],
            [
                'title' => 'Final Virtual Tour: Ilocos Norte', 
                'scenarios' => [], 
                'status' => 'published',
                'passing_score' => 80
            ]
        );

        $user = \Illuminate\Support\Facades\Auth::user();

        // 1. Get up to 5 completed towns
        $completedTowns = \App\Models\ModuleProgress::where('user_id', $user->id)
            ->whereHas('courseModule', function ($q) {
                $q->where('type', 'town_chapter');
            })
            ->where('passed', true)
            ->with('courseModule')
            ->inRandomOrder()
            ->take(5)
            ->get();

        $townSlugs = $completedTowns->map(function($progress) {
            return str_replace('town-', '', $progress->courseModule->code);
        });

        $townSimulations = collect();
        if ($townSlugs->isNotEmpty()) {
            $townSimulations = \App\Models\Simulation::where('type', 'town')
                ->whereHas('town', function($q) use ($townSlugs) {
                    $q->whereIn('slug', $townSlugs);
                })
                ->get();
        }

        // Fallback for demo mode if no completed towns
        if ($townSimulations->isEmpty()) {
            $townSimulations = \App\Models\Simulation::where('type', 'town')
                ->inRandomOrder()
                ->take(5)
                ->get();
        }

        $finalScenarios = [];
        
        // 2. Extract up to 2 random scenarios from each town
        foreach ($townSimulations as $ts) {
            $scens = is_string($ts->scenarios) ? json_decode($ts->scenarios, true) : $ts->scenarios;
            if (!is_array($scens)) continue;
            
            shuffle($scens);
            $taken = array_slice($scens, 0, 2);
            
            $module = \App\Models\CourseModule::where('code', 'town-' . $ts->town->slug)->first();
            
            foreach ($taken as $scenario) {
                $lesson = \App\Models\ModuleLesson::find($scenario['lesson_id'] ?? null);
                $keywords = [];
                if (!empty($scenario['keywords'])) {
                    if (is_string($scenario['keywords'])) {
                        $parsed = array_values(array_filter(array_map('trim', explode(',', $scenario['keywords']))));
                        foreach ($parsed as $word) {
                            $keywords[] = ['word' => $word, 'points' => 10, 'aliases' => []];
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

                $finalScenarios[] = [
                    'is_surprise' => false,
                    'title' => ($scenario['title'] ?? 'Attraction') . ' (' . $ts->town->name . ')',
                    'image' => $image,
                    'keywords' => $keywords,
                    'time_limit' => (int)($scenario['time_limit'] ?? 60),
                ];
            }
        }

        // 3. Inject Surprise Questions from Question Bank
        $surpriseCount = min(5, max(1, count($townSimulations)));
        $questions = \App\Models\QuizQuestion::inRandomOrder()->take($surpriseCount)->get();

        foreach ($questions as $q) {
            $correctOptionField = 'option_' . $q->correct_option; // option_a, option_b...
            $correctAnswerText = $q->$correctOptionField;
            
            if (!$correctAnswerText) continue;

            $finalScenarios[] = [
                'is_surprise' => true,
                'title' => 'Surprise Tourist Question!',
                'question' => $q->question_text ?? $q->question,
                'options' => [
                    'a' => $q->option_a,
                    'b' => $q->option_b,
                    'c' => $q->option_c,
                    'd' => $q->option_d,
                ],
                'correct_option' => $q->correct_option,
                'image' => '/assets/images/INBackground.jpg', // Standard background for questions
                'keywords' => [
                    ['word' => $correctAnswerText, 'points' => 20, 'aliases' => []]
                ],
                'time_limit' => 30, // 30 seconds to answer
            ];
        }

        // Shuffle all scenarios so questions are interleaved
        shuffle($finalScenarios);

        $simulationArray = $simulation->toArray();
        $simulationArray['scenarios'] = $finalScenarios;

        return Inertia::render('Student/Simulation', [
            'simulation' => $simulationArray,
        ]);
    }

    public function validateSpeech(Request $request)
    {
        $validated = $request->validate([
            'transcript' => 'required|string',
            'required_keywords' => 'required|array',
            'required_keywords.*.word' => 'required|string',
            'required_keywords.*.points' => 'required|numeric',
            'required_keywords.*.aliases' => 'nullable|array',
            'required_keywords.*.aliases.*' => 'string',
        ]);

        $transcript = strtolower($validated['transcript']);
        $transcript = preg_replace('/\bkilometers?\b/', 'km', $transcript);
        $transcript = preg_replace('/\bmeters?\b/', 'm', $transcript);
        $cleanTranscript = preg_replace('/[\s.,\/#!$%\^&\*;:{}=\-_`~()]/', '', $transcript);
        
        $matchedKeywords = [];
        $totalPoints = 0;
        $earnedPoints = 0;

        foreach ($validated['required_keywords'] as $kw) {
            $word = strtolower(trim($kw['word']));
            $word = preg_replace('/\bkilometers?\b/', 'km', $word);
            $word = preg_replace('/\bmeters?\b/', 'm', $word);
            $cleanKw = preg_replace('/[\s.,\/#!$%\^&\*;:{}=\-_`~()]/', '', $word);
            $points = (int)$kw['points'];
            $totalPoints += $points;
            
            $matched = false;
            
            // Check exact word
            if (str_contains($cleanTranscript, $cleanKw)) {
                $matched = true;
            } else if (str_contains($cleanTranscript, \Illuminate\Support\Str::singular($cleanKw)) || str_contains($cleanTranscript, \Illuminate\Support\Str::plural($cleanKw))) {
                $matched = true;
            } else {
                // Tokenize for long phrases/sentences
                $kwTokens = array_filter(explode(' ', trim(preg_replace('/[^a-z0-9\s]/', '', $word))));
                $stopWords = ['the','a','an','and','or','in','on','at','to','for','of','with','by','is','are','was','were','it'];
                $significantTokens = array_diff($kwTokens, $stopWords);
                
                if (count($significantTokens) > 2) {
                    $hitCount = 0;
                    foreach ($significantTokens as $token) {
                        $token = trim($token);
                        $tokenSing = \Illuminate\Support\Str::singular($token);
                        $tokenPlur = \Illuminate\Support\Str::plural($token);
                        if (str_contains($cleanTranscript, $token) || str_contains($cleanTranscript, $tokenSing) || str_contains($cleanTranscript, $tokenPlur)) {
                            $hitCount++;
                        }
                    }
                    if ($hitCount / count($significantTokens) >= 0.6) {
                        $matched = true;
                    }
                }
            }
            
            if (!$matched && !empty($kw['aliases']) && is_array($kw['aliases'])) {
                // Check aliases
                foreach ($kw['aliases'] as $alias) {
                    $alias = strtolower(trim($alias));
                    $alias = preg_replace('/\bkilometers?\b/', 'km', $alias);
                    $alias = preg_replace('/\bmeters?\b/', 'm', $alias);
                    $cleanAlias = preg_replace('/[\s.,\/#!$%\^&\*;:{}=\-_`~()]/', '', $alias);
                    if (!empty($cleanAlias) && str_contains($cleanTranscript, $cleanAlias)) {
                        $matched = true;
                        break;
                    }
                }
            }
            
            if ($matched) {
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
        $gamificationResult = null;
        if ($user && $xpEarned > 0) {
            $gamification = new \App\Services\GamificationService();
            $gamificationResult = $gamification->awardXp($user, $xpEarned, 'Speech keywords matched');
        }

        return response()->json([
            'success' => true,
            'matched_keywords' => $matchedKeywords,
            'match_count' => $matchCount,
            'score_percent' => $scorePercent,
            'xp_earned' => $xpEarned,
            'gamification' => $gamificationResult
        ]);
    }

    public function complete(Request $request, string $id)
    {
        /** @var \App\Models\User $user */
        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user) {
            $simulation = \App\Models\Simulation::with('town')->findOrFail($id);
            $passed = $request->input('passed', false);
            $score = (int) $request->input('score', 0);

            $record = \Illuminate\Support\Facades\DB::table('simulation_user')
                ->where('user_id', $user->id)
                ->where('simulation_id', $simulation->id)
                ->first();

            if (!$record) {
                \Illuminate\Support\Facades\DB::table('simulation_user')->insert([
                    'user_id' => $user->id,
                    'simulation_id' => $simulation->id,
                    'passed' => $passed,
                    'score' => $score,
                    'attempts' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                \Illuminate\Support\Facades\DB::table('simulation_user')
                    ->where('id', $record->id)
                    ->update([
                        'passed' => $record->passed || $passed,
                        'score' => $record->passed ? $record->score : $score,
                        'attempts' => $record->attempts + 1,
                        'updated_at' => now(),
                    ]);
            }

            // Always ensure the town module progress is recorded if they pass
            if ($passed) {
                if ($simulation->type === 'town' && $simulation->town_id) {
                    $module = \App\Models\CourseModule::where('type', 'town_chapter')
                        ->where('code', 'town-' . $simulation->town->slug)
                        ->first();
                        
                    if (!$module) {
                        $module = \App\Models\CourseModule::where('type', 'town_chapter')
                            ->where('code', 'like', 'town-' . $simulation->town->slug . '%')
                            ->first();
                    }
                    
                    if ($module) {
                        \App\Models\ModuleProgress::updateOrCreate(
                            ['user_id' => $user->id, 'course_module_id' => $module->id],
                            ['passed' => true, 'score_percentage' => $score]
                        );
                    }
                }
            }

            $gamificationResult = null;
            // Only award progress if they just passed for the first time
            if ($passed && (!$record || !$record->passed)) {
                $gamification = new \App\Services\GamificationService();
                $baseXp = ($simulation->type === 'final') ? 500 : 100;
                $gamificationResult = $gamification->awardXp($user, $baseXp, 'Simulation completed');
            }
        }

        return response()->json([
            'success' => true,
            'gamification' => $gamificationResult ?? null
        ]);
    }
}
