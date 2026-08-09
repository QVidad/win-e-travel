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

        $completedChapters = $user->completed_chapters_count ?? 0;
        $totalChapters = 25;
        $overallProgress = $totalChapters > 0 ? (int) round(($completedChapters / $totalChapters) * 100) : 0;

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
                'completedChapters' => $completedChapters,
                'totalChapters' => $totalChapters,
                'overallPercentage' => $overallProgress,
                'foundationCompleted' => $user->foundation_completed_count ?? 0,
                'townsCompleted' => $user->towns_completed_count ?? 0,
                'simulationsUnlocked' => $user->simulations_unlocked_count ?? 0,
            ],
            'activities' => [],
        ]);
    }
}
