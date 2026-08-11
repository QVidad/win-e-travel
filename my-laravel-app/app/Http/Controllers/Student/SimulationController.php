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

    public function validateSpeech(Request $request)
    {
        $validated = $request->validate([
            'transcript' => 'required|string',
            'required_keywords' => 'required|array',
        ]);

        $transcript = strtolower($validated['transcript']);
        $matchedKeywords = [];

        foreach ($validated['required_keywords'] as $kw) {
            if (str_contains($transcript, strtolower($kw))) {
                $matchedKeywords[] = $kw;
            }
        }

        $totalRequired = count($validated['required_keywords']);
        $matchCount = count($matchedKeywords);
        $scorePercent = $totalRequired > 0 ? (int) round(($matchCount / $totalRequired) * 100) : 100;
        $xpEarned = $matchCount * 25;

        // Update authenticated user stats if available
        $user = auth()->user();
        if ($user) {
            $user->increment('xp', $xpEarned);
        }

        return response()->json([
            'success' => true,
            'matched_keywords' => $matchedKeywords,
            'match_count' => $matchCount,
            'score_percent' => $scorePercent,
            'xp_earned' => $xpEarned,
        ]);
    }
}
