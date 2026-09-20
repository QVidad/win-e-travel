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
            
        $unlockedLevel = 1;
        foreach ($foundationModules as $index => $module) {
            $isPassed = $progresses[$module->id] ?? false;
            if ($isPassed) {
                $unlockedLevel = $index + 2;
            } else {
                break;
            }
        }

        // Map tags
        $foundationModules->map(function ($module) {
            $module->tags = $module->lessons->pluck('title')->toArray();
            return $module;
        });

        return Inertia::render('Student/Foundation', [
            'foundationModules' => $foundationModules,
            'unlockedLevel' => $unlockedLevel,
        ]);
    }
}
