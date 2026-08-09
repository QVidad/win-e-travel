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
        $towns = Town::with('destinations')->where('status', 'published')->orderBy('order')->get();

        return Inertia::render('Student/Towns/Index', [
            'towns' => $towns,
        ]);
    }

    public function show(string $slug): Response
    {
        $town = Town::with(['destinations' => function ($query) {
            $query->where('is_visible', true)->orderBy('order');
        }])->where('slug', $slug)->firstOrFail();

        return Inertia::render('Student/Towns/Show', [
            'town' => $town,
        ]);
    }
}
