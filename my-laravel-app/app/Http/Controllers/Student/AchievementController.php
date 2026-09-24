<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use Inertia\Inertia;
use Inertia\Response;

class AchievementController extends Controller
{
    public function index(): Response
    {
        /** @var \App\Models\User $user */
        $user = \Illuminate\Support\Facades\Auth::user();

        $unlockedIds = \Illuminate\Support\Facades\DB::table('user_achievements')
            ->where('user_id', $user->id)
            ->pluck('achievement_id')
            ->toArray();

        $achievements = Achievement::orderBy('order')->get()->map(function ($ach) use ($unlockedIds) {
            $ach->is_unlocked = in_array($ach->id, $unlockedIds);
            return $ach;
        });

        // Calculate progress for certificate
        $foundationTotal = \App\Models\CourseModule::where('type', 'foundation')->where('status', 'published')->count();
        $townsTotal = \App\Models\CourseModule::where('type', 'town_chapter')->where('status', 'published')->count();
        $finalSimulation = \App\Models\Simulation::where('type', 'final')->first();
        
        $foundationCompleted = \App\Models\ModuleProgress::where('user_id', $user->id)
            ->whereHas('courseModule', function ($q) {
                $q->where('type', 'foundation');
            })->where('passed', true)->count();
            
        $townsCompleted = \App\Models\ModuleProgress::where('user_id', $user->id)
            ->whereHas('courseModule', function ($q) {
                $q->where('type', 'town_chapter');
            })->where('passed', true)->count();
            
        $finalPassed = false;
        if ($finalSimulation) {
            $finalPassed = \Illuminate\Support\Facades\DB::table('simulation_user')
                ->where('user_id', $user->id)
                ->where('simulation_id', $finalSimulation->id)
                ->where('passed', true)
                ->exists();
        }
        
        $totalItems = $foundationTotal + $townsTotal + ($finalSimulation ? 1 : 0);
        $completedItems = $foundationCompleted + $townsCompleted + ($finalPassed ? 1 : 0);
        
        $progressPercentage = $totalItems > 0 ? (int)round(($completedItems / $totalItems) * 100) : 0;

        $certificateSettings = \App\Models\CertificateSetting::first();

        return Inertia::render('Student/Achievements', [
            'achievements' => $achievements,
            'stats' => [
                'totalEarned' => count($unlockedIds),
                'totalAvailable' => count($achievements),
                'totalXp' => $user->xp ?? 450,
                'currentRank' => 'Junior Tour Guide',
            ],
            'userStats' => [
                'xp' => $user->xp ?? 0,
                'level' => $user->level ?? 1,
            ],
            'certificateProgress' => $progressPercentage,
            'certificateSettings' => $certificateSettings,
        ]);
    }
}
