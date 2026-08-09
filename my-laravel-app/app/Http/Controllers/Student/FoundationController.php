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
        $overview = ContentSection::where('page_key', 'foundation')->where('section_key', 'overview')->first();

        return Inertia::render('Student/Foundation', [
            'overview' => $overview,
        ]);
    }
}
