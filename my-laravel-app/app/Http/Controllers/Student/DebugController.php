<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DebugController extends Controller
{
    public function debugFinalBoss()
    {
        // CLEANUP DUPLICATES: Keep only the FIRST simulation (lowest ID) for each town, 
        // to match what Educator Panel edits. Delete the rest.
        $towns = \App\Models\Town::all();
        foreach ($towns as $town) {
            $sims = \App\Models\Simulation::where('town_id', $town->id)->where('type', 'town')->orderBy('id', 'asc')->get();
            if ($sims->count() > 1) {
                // Keep the first one, delete the rest
                $first = $sims->shift();
                foreach ($sims as $duplicate) {
                    $duplicate->delete();
                }
            }
        }

        $townSimulations = \App\Models\Simulation::where('type', 'town')
            ->orderBy('updated_at', 'desc')
            ->get();
        
        $debug = [];
        foreach ($townSimulations as $ts) {
            $scens = is_string($ts->scenarios) ? json_decode($ts->scenarios, true) : $ts->scenarios;
            $debug[] = [
                'simulation_id' => $ts->id,
                'town_name' => $ts->town ? $ts->town->name : 'Unknown',
                'updated_at' => $ts->updated_at,
                'scenarios' => $scens
            ];
        }
        return response()->json($debug);
    }
}
