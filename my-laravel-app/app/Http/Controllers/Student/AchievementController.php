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
        ]);
    }
}
