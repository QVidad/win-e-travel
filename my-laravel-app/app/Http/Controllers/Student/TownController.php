<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Town;
use Inertia\Inertia;
use Inertia\Response;

class TownController extends Controller
{
    public function index(): Response|\Illuminate\Http\RedirectResponse
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        
        // Progression Lock Check
        $foundationTotal = \App\Models\CourseModule::where('type', 'foundation')->where('status', 'published')->count();
        $foundationCompleted = \App\Models\ModuleProgress::where('user_id', $user->id)
            ->whereHas('courseModule', function ($q) {
                $q->where('type', 'foundation');
            })
            ->where('passed', true)
            ->count();
            
        if ($foundationTotal > 0 && $foundationCompleted < $foundationTotal) {
            return redirect()->route('dashboard')->with('error', 'You must complete all Go Beyond Books foundation modules first.');
        }
        
        $publishedModules = \App\Models\CourseModule::with('lessons')
            ->where('type', 'town_chapter')
            ->where('status', 'published')
            ->get()
            ->keyBy('code');

        $towns = Town::with('destinations')
            ->where('status', 'published')
            ->orderBy('order')
            ->get()
            ->filter(function ($town) use ($publishedModules) {
                return $publishedModules->has('town-' . $town->slug);
            })->map(function ($town) use ($publishedModules) {
                $module = $publishedModules['town-' . $town->slug];
                $town->module = $module;
                
                // Override seeded destinations with the actual Educator-created attractions (lessons)
                $customDestinations = $module->lessons->map(function($lesson) {
                    return [
                        'id' => 'lesson-' . $lesson->id,
                        'name' => $lesson->title,
                    ];
                });
                $town->setRelation('destinations', collect($customDestinations));
                
                return $town;
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
                if ($isNextAvailable) {
                    $status = 'available';
                    $isNextAvailable = false;
                } else {
                    $status = 'locked';
                }
            }
            
            $town->progress_status = $status;
            
            return $town;
        });

        return Inertia::render('Student/Towns/Index', [
            'towns' => $mappedTowns,
            'completedCount' => $completedCount,
        ]);
    }

    public function show(string $slug): Response|\Illuminate\Http\RedirectResponse
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        
        // Progression Lock Check
        $foundationTotal = \App\Models\CourseModule::where('type', 'foundation')->where('status', 'published')->count();
        $foundationCompleted = \App\Models\ModuleProgress::where('user_id', $user->id)
            ->whereHas('courseModule', function ($q) {
                $q->where('type', 'foundation');
            })
            ->where('passed', true)
            ->count();
            
        if ($foundationTotal > 0 && $foundationCompleted < $foundationTotal) {
            return redirect()->route('dashboard')->with('error', 'You must complete all Go Beyond Books foundation modules first.');
        }

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
