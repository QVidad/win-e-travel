<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ContentSection;
use Inertia\Inertia;
use Inertia\Response;

class FoundationController extends Controller
{
    public function index(): Response
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $isEducatorOrAdmin = in_array($user->role, ['educator', 'admin']);
        
        $foundationModules = \App\Models\CourseModule::where('type', 'foundation')
            ->where('status', 'published')
            ->with(['lessons' => function ($q) {
                $q->select('id', 'course_module_id', 'title', 'order')->orderBy('order');
            }])
            ->orderBy('order')
            ->get();
            
        $progresses = \App\Models\ModuleProgress::where('user_id', $user->id)
            ->whereHas('courseModule', function ($q) {
                $q->where('type', 'foundation');
            })
            ->pluck('passed', 'course_module_id')
            ->toArray();
            
        $unlockedLevel = $isEducatorOrAdmin ? (count($foundationModules) + 1) : 1;
        if (!$isEducatorOrAdmin) {
            foreach ($foundationModules as $index => $module) {
                $isPassed = $progresses[$module->id] ?? false;
                if ($isPassed) {
                    $unlockedLevel = $index + 2;
                } else {
                    break;
                }
            }
        }

        // Map tags and completion status
        $foundationModules->map(function ($module) use ($progresses) {
            $module->tags = $module->lessons->pluck('title')->toArray();
            $module->is_passed = (bool)($progresses[$module->id] ?? false);
            return $module;
        });

        return Inertia::render('Student/Foundation', [
            'foundationModules' => $foundationModules,
            'unlockedLevel' => $unlockedLevel,
            'isEducatorOrAdmin' => $isEducatorOrAdmin,
        ]);
    }
}
