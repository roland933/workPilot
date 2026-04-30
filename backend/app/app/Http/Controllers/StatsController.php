<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TimeEntry;

class StatsController extends Controller
{
    public function index()
        {
                $entries = TimeEntry::with('project')
                ->where('user_id', auth()->id())
                ->whereNotNull('duration')
                ->get();

                $grouped = $entries->groupBy('project_id');

                $projects = $grouped->map(function ($entries) {
                    $project = $entries->first()->project;

                    $totalSeconds = $entries->sum('duration');

                    $hours = $totalSeconds / 3600;
                    $earnings = $hours * $project->hourly_rate;

                    return [
                        'project' => $project->name,
                        'hours' => round($hours, 2),
                        'earnings' => round($earnings, 2),
                    ];
                })->values();


                return response()->json($projects);
        }
}
