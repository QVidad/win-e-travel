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
        $foundationModules = \App\Models\CourseModule::where('type', 'foundation')
            ->orderBy('order')
            ->get();

        return Inertia::render('Student/Foundation', [
            'foundationModules' => $foundationModules,
        ]);
    }
}
