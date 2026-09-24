<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Models\Town;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class StudentDashboardController extends Controller
{
    public function index(Request $request): Response
    {
        $user = Auth::user();
        $towns = Town::where('status', 'published')->orderBy('order')->get();
        $achievements = Achievement::orderBy('order')->take(4)->get();

        $completedChapters = \App\Models\ModuleProgress::where('user_id', $user->id)
            ->where('passed', true)
            ->count();
            
        $totalChapters = \App\Models\CourseModule::where('status', 'published')->count();
        
        $finalSimulation = \App\Models\Simulation::where('type', 'final')->where('status', 'published')->first();
        $finalSimPassed = false;
        
        if ($finalSimulation) {
            $totalChapters += 1;
            $finalSimPassed = \Illuminate\Support\Facades\DB::table('simulation_user')
                ->where('user_id', $user->id)
                ->where('simulation_id', $finalSimulation->id)
                ->where('passed', true)
                ->exists();
            if ($finalSimPassed) {
                $completedChapters += 1;
            }
        }

        if ($totalChapters === 0) {
            $totalChapters = 1; // avoid division by zero
        }

        $overallProgress = (int) round(($completedChapters / $totalChapters) * 100);

        $foundationCompleted = \App\Models\ModuleProgress::where('user_id', $user->id)
            ->where('passed', true)
            ->whereHas('courseModule', function ($query) {
                $query->where('type', 'foundation');
            })->count();

        $townsCompleted = \App\Models\ModuleProgress::where('user_id', $user->id)
            ->where('passed', true)
            ->whereHas('courseModule', function ($query) {
                $query->where('type', 'town_chapter');
            })->count();

        $foundationTotal = \App\Models\CourseModule::where('type', 'foundation')->where('status', 'published')->count();
        $townsTotal = \App\Models\CourseModule::where('type', 'town_chapter')->where('status', 'published')->count();
        $townSimulationsTotal = \App\Models\Simulation::where('type', 'town')->where('status', 'published')->count();
        
        // Modules give 50 XP. Town simulations give 100 XP. Final simulation gives 500 XP.
        $maxTargetXp = ($foundationTotal * 50) + ($townsTotal * 50) + ($townSimulationsTotal * 100) + ($finalSimulation ? 500 : 0);

        $hasStarted = \App\Models\ModuleProgress::where('user_id', $user->id)->exists();
        
        // Give a 1% motivational bump if they have started but haven't fully completed a chapter yet
        if ($overallProgress === 0 && $hasStarted) {
            $overallProgress = 1;
        }

        // Determine where the user left off
        $latestProgress = \App\Models\ModuleProgress::where('user_id', $user->id)
            ->with('courseModule')
            ->orderBy('updated_at', 'desc')
            ->first();
            
        $continueModule = null;
        if ($latestProgress) {
            if ($latestProgress->passed) {
                $continueModule = \App\Models\CourseModule::where('status', 'published')
                    ->where('order', '>', $latestProgress->courseModule->order)
                    ->orderBy('order')
                    ->first();
            } else {
                $continueModule = $latestProgress->courseModule;
            }
        }
        
        $continueModuleArray = null;
        if ($continueModule) {
            $continueModuleArray = [
                'id' => $continueModule->id,
                'title' => $continueModule->title,
                'type' => $continueModule->type,
                'code' => $continueModule->code,
            ];
        } else if ($hasStarted && $townsCompleted >= $townsTotal && $finalSimulation && !$finalSimPassed) {
            $continueModuleArray = [
                'id' => $finalSimulation->id,
                'title' => 'Adventure Awaits (Final Tour)',
                'type' => 'final_simulation',
                'code' => 'final',
            ];
        } else if (!$hasStarted) {
            $firstModule = \App\Models\CourseModule::where('status', 'published')->orderBy('order')->first();
            if ($firstModule) {
                $continueModuleArray = [
                    'id' => $firstModule->id,
                    'title' => $firstModule->title,
                    'type' => $firstModule->type,
                    'code' => $firstModule->code,
                ];
            }
        }



        return Inertia::render('Student/Dashboard', [
            'towns' => $towns,
            'achievements' => $achievements,
            'userStats' => [
                'completedModules' => $completedChapters,
                'totalTowns' => count($towns),
                'xp' => $user->xp ?? 0,
                'streakDays' => $user->streak_days ?? 0,
            ],
            'progress' => [
                'hasStarted' => $hasStarted,
                'completedChapters' => $completedChapters,
                'totalChapters' => $totalChapters,
                'overallPercentage' => $overallProgress,
                'foundationCompleted' => $foundationCompleted,
                'foundationTotal' => $foundationTotal,
                'townsCompleted' => $townsCompleted,
                'townsTotal' => $townsTotal,
                'maxTargetXp' => $maxTargetXp,
                'simulationsCompleted' => $finalSimPassed ? 1 : 0,
                'simulationsTotal' => $finalSimulation ? 1 : 0,
                'finalSimulationId' => $finalSimulation ? $finalSimulation->id : null,
                'continueModule' => $continueModuleArray,
            ],
            'activities' => [],
        ]);
    }
}
