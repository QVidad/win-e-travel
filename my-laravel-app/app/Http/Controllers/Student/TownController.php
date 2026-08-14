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
        $towns = Town::with('destinations')->where('status', 'published')->orderBy('order')->get();
        
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

        return Inertia::render('Student/Towns/Show', [
            'town' => $town,
        ]);
    }
}
