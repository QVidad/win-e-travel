<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Models\Town;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StudentDashboardController extends Controller
{
    public function index(Request $request): Response
    {
        $towns = Town::where('status', 'published')->orderBy('order')->get();
        $achievements = Achievement::orderBy('order')->take(4)->get();

        return Inertia::render('Student/Dashboard', [
            'towns' => $towns,
            'achievements' => $achievements,
            'userStats' => [
                'completedModules' => 2,
                'totalTowns' => count($towns),
                'xp' => 450,
                'streakDays' => 5,
            ],
        ]);
    }
}
