<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Town;
use Inertia\Inertia;
use Inertia\Response;

class TownController extends Controller
{
    public function index(): Response
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        
        $publishedModuleCodes = \App\Models\CourseModule::where('type', 'town_chapter')
            ->where('status', 'published')
            ->pluck('code')
            ->toArray();

        $towns = Town::with('destinations')
            ->where('status', 'published')
            ->orderBy('order')
            ->get()
            ->filter(function ($town) use ($publishedModuleCodes) {
                return in_array('town-' . $town->slug, $publishedModuleCodes);
            })->values();
        // Fetch user progress for town modules
        $progresses = \App\Models\ModuleProgress::where('user_id', $user->id)
            ->whereHas('courseModule', function ($q) {
                $q->where('type', 'town_chapter');
            })
            ->with('courseModule')
            ->get();
            
        // Map progress by town code
        $progressByCode = [];
        foreach ($progresses as $prog) {
            if ($prog->courseModule) {
                $progressByCode[$prog->courseModule->code] = $prog;
            }
        }

        $completedCount = 0;
        $isNextAvailable = true; // The first town is always available initially

        // Sequentially determine status
        $mappedTowns = $towns->map(function ($town) use (&$completedCount, &$isNextAvailable, $progressByCode) {
            $code = 'town-' . $town->slug;
            $prog = $progressByCode[$code] ?? null;
            
            $isCompleted = $prog && $prog->passed;
            
            if ($isCompleted) {
                $status = 'completed';
                $completedCount++;
            } else {
                // TEMPORARY BYPASS: All towns available for testing
                $status = 'available';
            }
            
            $town->progress_status = $status;
            
            return $town;
        });

        return Inertia::render('Student/Towns/Index', [
            'towns' => $mappedTowns,
            'completedCount' => $completedCount,
        ]);
    }

    public function show(string $slug): Response
    {
        $town = Town::with(['destinations' => function ($query) {
            $query->where('is_visible', true)->orderBy('order');
        }, 'simulation'])->where('slug', $slug)->firstOrFail();

        $module = \App\Models\CourseModule::with(['lessons' => function ($query) {
            $query->orderBy('order');
        }])->where('code', 'town-' . $slug)->where('status', 'published')->firstOrFail();

        $progress = \App\Models\ModuleProgress::firstOrCreate(
            ['user_id' => \Illuminate\Support\Facades\Auth::id(), 'course_module_id' => $module->id],
            ['passed' => false, 'status' => 'in_progress', 'progress' => 0]
        );

        $isCompleted = $progress->passed;

        return Inertia::render('Student/Towns/Show', [
            'town' => $town,
            'module' => $module,
            'isCompleted' => $isCompleted,
        ]);
    }
}
