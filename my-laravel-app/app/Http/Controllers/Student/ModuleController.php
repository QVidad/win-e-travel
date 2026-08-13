<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\CourseModule;
use App\Models\ModuleProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response;

class ModuleController extends Controller
{
    /**
     * Display listing of published modules for students.
     */
    public function index(): Response
    {
        $modules = Cache::remember('published_student_modules', 3600, function () {
            return CourseModule::where('status', 'published')
                ->with('questions')
                ->orderBy('order')
                ->get();
        });

        $user = Auth::user();
        $userProgress = ModuleProgress::where('user_id', $user->id)->get()->keyBy('course_module_id');

        return Inertia::render('Student/Modules/Index', [
            'modules' => $modules,
            'userProgress' => $userProgress,
        ]);
    }

    /**
     * Display a specific published module for students.
     * Throws 404 if module does not exist or is currently in draft state.
     */
    public function show($id): Response
    {
        $module = CourseModule::where('status', 'published')
            ->with(['questions', 'lessons.questions' => function ($q) {
                $q->orderBy('id');
            }])
            ->where(function ($query) use ($id) {
                $query->where('id', $id)->orWhere('code', $id);
            })
            ->firstOrFail();

        $user = Auth::user();
        $progress = ModuleProgress::where('user_id', $user->id)
            ->where('course_module_id', $module->id)
            ->first();

        return Inertia::render('Student/Modules/Show', [
            'module' => [
                'id' => $module->id,
                'code' => $module->code,
                'type' => $module->type,
                'title' => $module->title,
                'subtitle' => $module->subtitle,
                'description' => $module->description,
                'key_spots' => $module->key_spots,
                'cover_image' => $module->cover_image,
                'icon' => $module->icon,
                'status' => $module->status,
                'questions' => $module->questions,
                'lessons' => $module->lessons,
            ],
            'userProgress' => $progress,
        ]);
    }

    /**
     * Save student progress for a specific module (lessons and evaluations).
     */
    public function saveProgress(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $module = CourseModule::findOrFail($id);
        $user = Auth::user();

        $validated = $request->validate([
            'lesson_data' => 'nullable|array',
            'score_percentage' => 'nullable|numeric',
            'passed' => 'nullable|boolean',
            'unlocked' => 'nullable|boolean',
        ]);

        $progress = ModuleProgress::firstOrCreate(
            ['user_id' => $user->id, 'course_module_id' => $module->id],
            ['lesson_data' => []]
        );

        if (array_key_exists('lesson_data', $validated)) {
            $progress->lesson_data = $validated['lesson_data'];
        }
        if (array_key_exists('score_percentage', $validated)) {
            $progress->score_percentage = $validated['score_percentage'];
        }
        if (array_key_exists('passed', $validated)) {
            $progress->passed = $validated['passed'];
        }
        if (array_key_exists('unlocked', $validated)) {
            $progress->unlocked = $validated['unlocked'];
        }

        $progress->save();

        return redirect()->back();
    }
}
