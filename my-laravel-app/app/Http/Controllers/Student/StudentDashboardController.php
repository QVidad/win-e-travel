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
        
        if (!$continueModule) {
            $continueModule = \App\Models\CourseModule::where('status', 'published')->orderBy('order')->first();
        }

        $finalSimulation = \App\Models\Simulation::where('type', 'final')->first();

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
                'townsCompleted' => $townsCompleted,
                'simulationsUnlocked' => $user->simulations_completed ?? 0,
                'finalSimulationId' => $finalSimulation ? $finalSimulation->id : null,
                'continueModule' => $continueModule ? [
                    'id' => $continueModule->id,
                    'title' => $continueModule->title,
                    'type' => $continueModule->type,
                ] : null,
            ],
            'activities' => [],
        ]);
    }
}
