<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'isFinalBossUnlocked' => function () use ($request) {
                if (!$request->user() || $request->user()->role !== 'student') {
                    return false;
                }
                
                return true; // TEMP UNLOCK FOR TESTING
                
                $townsCount = \App\Models\CourseModule::where('type', 'town_chapter')->where('status', 'published')->count();
                $completedTownsCount = \App\Models\ModuleProgress::where('user_id', $request->user()->id)
                    ->whereHas('courseModule', function ($q) {
                        $q->where('type', 'town_chapter');
                    })
                    ->where('passed', true)
                    ->count();

                return $townsCount > 0 && $completedTownsCount >= $townsCount;
            },
            'flash' => [
                'gamification' => $request->session()->get('gamification'),
                'success' => $request->session()->get('success'),
                'error' => $request->session()->get('error'),
            ],
        ];
    }
}
