<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ContentSection;
use App\Models\Town;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        $hero = ContentSection::where('page_key', 'home')->where('section_key', 'hero')->first();
        $features = ContentSection::where('page_key', 'home')->where('section_key', 'features')->first();
        $featuredTowns = Town::where('status', 'published')->orderBy('order')->take(6)->get();

        return Inertia::render('Student/Index', [
            'hero' => $hero,
            'features' => $features,
            'featuredTowns' => $featuredTowns,
        ]);
    }
}
