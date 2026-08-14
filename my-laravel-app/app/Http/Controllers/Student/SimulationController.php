<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Town;
use Inertia\Inertia;
use Inertia\Response;

class SimulationController extends Controller
{
    public function index(): Response
    {
        $user = auth()->user();
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

    public function show($id): Response
    {
        $simulation = \App\Models\Simulation::findOrFail($id);

        return Inertia::render('Student/Simulation', [
            'simulation' => $simulation,
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
        ]);

        $transcript = strtolower($validated['transcript']);
        $matchedKeywords = [];

        foreach ($validated['required_keywords'] as $kw) {
            if (str_contains($transcript, strtolower($kw))) {
                $matchedKeywords[] = $kw;
            }
        }

        $totalRequired = count($validated['required_keywords']);
        $matchCount = count($matchedKeywords);
        $scorePercent = $totalRequired > 0 ? (int) round(($matchCount / $totalRequired) * 100) : 100;
        $xpEarned = $matchCount * 25;

        // Update authenticated user stats if available
        $user = auth()->user();
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

    public function complete(Request $request, $id)
    {
        $user = auth()->user();
        if ($user) {
            $simulation = \App\Models\Simulation::findOrFail($id);

            // Check if already passed to avoid double counting XP
            $alreadyPassed = \Illuminate\Support\Facades\DB::table('simulation_user')
                ->where('user_id', $user->id)
                ->where('simulation_id', $simulation->id)
                ->where('passed', true)
                ->exists();

            if (!$alreadyPassed) {
                \Illuminate\Support\Facades\DB::table('simulation_user')->insert([
                    'user_id' => $user->id,
                    'simulation_id' => $simulation->id,
                    'passed' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $user->increment('xp', 150);

                // If it's a town simulation, we should mark the town module as passed 
                // or unlock the next town. Currently Dare to Discover handles progress 
                // in ModuleProgress. If the user finished the simulation, it means they finished the town.
                if ($simulation->type === 'town' && $simulation->town_id) {
                    // Update ModuleProgress for this town's module if it exists
                    $module = \App\Models\CourseModule::where('type', 'town_chapter')
                        ->where('title', 'like', '%' . $simulation->town->name . '%')
                        ->first();
                    
                    if ($module) {
                        \App\Models\ModuleProgress::updateOrCreate(
                            ['user_id' => $user->id, 'course_module_id' => $module->id],
                            ['passed' => true]
                        );
                    }
                }
            }
        }

        return response()->json(['success' => true]);
    }
}
