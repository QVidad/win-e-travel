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
        $achievements = Achievement::orderBy('order')->get();

        return Inertia::render('Student/Achievements', [
            'achievements' => $achievements,
            'stats' => [
                'totalEarned' => 3,
                'totalAvailable' => count($achievements),
                'totalXp' => 450,
                'currentRank' => 'Junior Tour Guide',
            ],
        ]);
    }
}
