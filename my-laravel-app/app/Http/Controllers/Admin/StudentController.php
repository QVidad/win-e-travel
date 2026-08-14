<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseModule;
use App\Models\ModuleProgress;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StudentController extends Controller
{
    /**
     * Display a listing of students with progress stats and certificate eligibility.
     */
    public function index(): Response
    {
        $students = User::where('role', 'student')
            ->latest()
            ->get();

        $totalModulesCount = CourseModule::where('status', 'published')->count();
        if ($totalModulesCount === 0) {
            $totalModulesCount = 25; // Default 4 Foundation + 21 Town Chapters
        }

        $studentData = $students->map(function ($student) use ($totalModulesCount) {
            $progressRecords = ModuleProgress::where('user_id', $student->id)->get();
            $completedCount = $progressRecords->where('passed', true)->count();
            
            $percentage = min(100, round(($completedCount / max(1, $totalModulesCount)) * 100));

            $certificateStatus = 'Ineligible';
            if ($completedCount >= $totalModulesCount) {
                $certificateStatus = 'Ready to Issue';
            } elseif ($completedCount > 0) {
                $certificateStatus = 'In Progress';
            }

            return [
                'id' => $student->id,
                'name' => $student->name,
                'email' => $student->email,
                'avatar' => $student->avatar ?? '/assets/images/facilitator-male.jpg',
                'status' => $student->status ?? 'active',
                'created_at' => $student->created_at,
                'completed_chapters' => $completedCount,
                'total_chapters' => $totalModulesCount,
                'overall_percentage' => $percentage,
                'certificate_status' => $certificateStatus,
                'progress_records' => $progressRecords->map(fn($p) => [
                    'module_id' => $p->course_module_id,
                    'score' => $p->score_percentage,
                    'passed' => $p->passed,
                ]),
            ];
        });

        $totalEnrolled = $studentData->count();
        $certifiedGraduates = $studentData->where('completed_chapters', '>=', $totalModulesCount)->count();
        $activeLearners = $studentData->where('completed_chapters', '>', 0)->where('completed_chapters', '<', $totalModulesCount)->count();

        return Inertia::render('Admin/Students', [
            'students' => $studentData->values(),
            'stats' => [
                'totalEnrolled' => $totalEnrolled,
                'activeLearners' => $activeLearners,
                'certifiedGraduates' => $certifiedGraduates,
                'totalModulesCount' => $totalModulesCount,
            ],
        ]);
    }

    /**
     * Delete or unenroll a student account.
     */
    public function destroy(string $id): RedirectResponse
    {
        $student = User::where('role', 'student')->findOrFail($id);
        
        // Remove progress and user
        ModuleProgress::where('user_id', $student->id)->delete();
        $student->delete();

        return redirect()->back()->with('success', 'Student account removed successfully.');
    }

    /**
     * Reset a student's quiz/module progress.
     */
    public function resetProgress(string $id): RedirectResponse
    {
        $student = User::where('role', 'student')->findOrFail($id);

        ModuleProgress::where('user_id', $student->id)->delete();

        return redirect()->back()->with('success', "Progress reset successfully for {$student->name}.");
    }
}
