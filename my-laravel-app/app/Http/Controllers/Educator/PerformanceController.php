<?php

namespace App\Http\Controllers\Educator;

use App\Http\Controllers\Controller;
use App\Models\CourseModule;
use App\Models\ModuleProgress;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PerformanceController extends Controller
{
    public function index(Request $request)
    {
        // Global metrics
        $students = User::where('role', 'student')->get();
        $studentIds = $students->pluck('id');
        
        $allProgress = ModuleProgress::whereIn('user_id', $studentIds)->get();
        $totalAttempts = $allProgress->count();
        
        $passedAttempts = $allProgress->where('score_percentage', '>=', 90)->count();
        $quizPassRate = $totalAttempts > 0 ? round(($passedAttempts / $totalAttempts) * 100) : 0;
        
        $classAverageScore = $totalAttempts > 0 ? round($allProgress->avg('score_percentage')) : 0;
        
        $totalModules = CourseModule::where('status', 'published')->count();
        
        $completedCertificates = 0;
        if ($totalModules > 0) {
            foreach ($students as $student) {
                $passedModulesCount = $allProgress->where('user_id', $student->id)->where('score_percentage', '>=', 90)->count();
                if ($passedModulesCount >= $totalModules) {
                    $completedCertificates++;
                }
            }
        }

        // Hardest Chapters (bottom 3 by pass rate)
        $hardestChapters = CourseModule::where('status', 'published')
            ->with(['progress' => function ($query) use ($studentIds) {
                $query->whereIn('user_id', $studentIds);
            }])
            ->get()
            ->map(function ($module) {
                $attempts = $module->progress->count();
                $avgScore = $attempts > 0 ? round($module->progress->avg('score_percentage')) : 0;
                $passed = $module->progress->where('score_percentage', '>=', 90)->count();
                $passRate = $attempts > 0 ? round(($passed / $attempts) * 100) : 0;
                
                return [
                    'title' => $module->title,
                    'code' => $module->code,
                    'average_score' => $avgScore,
                    'pass_rate' => $passRate,
                    'attempts' => $attempts,
                ];
            })
            ->sortBy('pass_rate')
            ->take(3)
            ->values();
            
        // Student Performance Roster
        $roster = $students->map(function ($student) use ($allProgress, $totalModules) {
            $studentProgress = $allProgress->where('user_id', $student->id);
            $attempts = $studentProgress->count();
            $avgScore = $attempts > 0 ? round($studentProgress->avg('score_percentage')) : 0;
            $completedChapters = $studentProgress->where('score_percentage', '>=', 90)->count();
            
            $status = ($totalModules > 0 && $completedChapters >= $totalModules) ? 'Eligible' : 'In Progress';
            
            $badgeColor = 'Red';
            if ($avgScore >= 90) {
                $badgeColor = 'Green';
            } elseif ($avgScore >= 75) {
                $badgeColor = 'Yellow';
            }
            
            return [
                'id' => $student->id,
                'name' => $student->name,
                'email' => $student->email,
                'avatar' => $student->avatar,
                'completed_chapters' => $completedChapters,
                'total_chapters' => $totalModules,
                'average_score' => $avgScore,
                'total_attempts' => $attempts,
                'badge_color' => $badgeColor,
                'status' => $status,
                'year_registered' => $student->created_at->format('Y'),
            ];
        });

        return Inertia::render('Educator/Performance/Index', [
            'classAverageScore' => $classAverageScore,
            'quizPassRate' => $quizPassRate,
            'completedCertificates' => $completedCertificates,
            'hardestChapters' => $hardestChapters,
            'roster' => $roster,
        ]);
    }

    public function show($id)
    {
        $student = User::where('role', 'student')->findOrFail($id);
        $modules = CourseModule::where('status', 'published')->get();
        $progressRecords = ModuleProgress::where('user_id', $student->id)->get();
        
        $totalModules = $modules->count();
        $completedModules = 0;
        
        $moduleDetails = $modules->map(function ($module) use ($progressRecords, &$completedModules) {
            $progress = $progressRecords->where('course_module_id', $module->id)->first();
            
            $score = $progress ? $progress->score_percentage : null;
            $passed = $progress ? $progress->score_percentage >= 90 : false;
            
            if ($passed) {
                $completedModules++;
            }
            
            return [
                'id' => $module->id,
                'code' => $module->code,
                'title' => $module->title,
                'score' => $score,
                'status' => $passed ? 'Passed' : ($progress ? 'Failed' : 'Not Attempted'),
                'completed_at' => ($passed && $progress) ? $progress->updated_at->format('M d, Y h:i A') : null,
                'timestamp' => ($passed && $progress) ? $progress->updated_at : null,
            ];
        });
        
        $status = ($totalModules > 0 && $completedModules >= $totalModules) ? 'Eligible' : 'In Progress';
        
        $dateRegistered = $student->created_at->format('M d, Y');
        
        $dateCompleted = null;
        if ($status === 'Eligible') {
            $lastPassedModule = $moduleDetails->where('status', 'Passed')->sortByDesc('timestamp')->first();
            if ($lastPassedModule) {
                $dateCompleted = $lastPassedModule['completed_at'];
            }
        }
        
        // Remove raw timestamps before sending to view
        $moduleDetails = $moduleDetails->map(function ($item) {
            unset($item['timestamp']);
            return $item;
        });

        return Inertia::render('Educator/Performance/Show', [
            'student' => [
                'id' => $student->id,
                'name' => $student->name,
                'email' => $student->email,
                'avatar' => $student->avatar,
                'status' => $status,
                'date_registered' => $dateRegistered,
                'date_completed' => $dateCompleted,
            ],
            'modules' => $moduleDetails,
            'summary' => [
                'completed_count' => $completedModules,
                'total_count' => $totalModules,
            ]
        ]);
    }
}
