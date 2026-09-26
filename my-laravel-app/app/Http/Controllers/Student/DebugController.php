<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DebugController extends Controller
{
    public function debugFinalBoss()
    {
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
