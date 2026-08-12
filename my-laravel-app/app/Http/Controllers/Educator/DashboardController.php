<?php

namespace App\Http\Controllers\Educator;

use App\Http\Controllers\Controller;
use App\Models\CourseModule;
use App\Models\ModuleProgress;
use App\Models\QuizQuestion;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Display the educator overview metrics and content activity.
     */
    public function index(): Response
    {
        $totalModules = CourseModule::count();
        $totalQuestions = QuizQuestion::count();
        $totalStudentAttempts = ModuleProgress::count();

        $recentModules = CourseModule::with('updatedBy')
            ->latest('updated_at')
            ->take(5)
            ->get();

        return Inertia::render('Educator/Dashboard', [
            'stats' => [
                'totalModules' => $totalModules,
                'totalQuestions' => $totalQuestions,
                'totalStudentAttempts' => $totalStudentAttempts,
                'publishedModulesCount' => CourseModule::where('status', 'published')->count(),
                'draftModulesCount' => CourseModule::where('status', 'draft')->count(),
            ],
            'recentModules' => $recentModules,
        ]);
    }
}
