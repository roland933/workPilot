<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TimeEntry;

class TimeEntryController
{
   

public function start(Request $request)
{
    // van-e már futó timer?
    $active = TimeEntry::where('user_id', auth()->id())
        ->whereNull('end_time')
        ->first();
 

    if ($active) {
        return response()->json([
            'error' => 'Timer already running'
        ], 400);
    }
   
    $entry = TimeEntry::create([
        'user_id' => auth()->id(),
        'project_id' => $request->project_id,
        'description' => $request->description,
        'start_time' => now(),
    ]);

    return response()->json($entry);
}

public function stop()
{
    $entry = TimeEntry::where('user_id', auth()->id())
        ->whereNull('end_time')
        ->first();

    if (!$entry) {
        return response()->json([
            'error' => 'No active timer'
        ], 400);
    }

    $entry->end_time = now();

    $entry->duration = abs($entry->end_time->diffInSeconds($entry->start_time));

    if ($entry->end_time < $entry->start_time) {
        return response()->json([
            'error' => 'Invalid time range'
        ], 400);
    }

    $entry->save();

    return response()->json($entry);
}

public function active()
{
    $entry = TimeEntry::where('user_id', auth()->id())
        ->whereNull('end_time')
        ->with('project')
        ->first();

    return response()->json($entry);
}



}
