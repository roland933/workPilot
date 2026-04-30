<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TimeEntry;

class TimeEntryController extends Controller
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

    $entry->duration = $entry->end_time->diffInSeconds($entry->start_time);

    $entry->save();

    return response()->json($entry);
}



}
