<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Town;
use Inertia\Inertia;
use Inertia\Response;

class SimulationController extends Controller
{
    public function index(): Response
    {
        $towns = Town::where('status', 'published')->get(['id', 'slug', 'name']);

        return Inertia::render('Student/Simulation', [
            'towns' => $towns,
        ]);
    }
}
