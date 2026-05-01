<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Project;

class ProjectController
{
   public function index()
    {   
        
        return auth()->user()->projects;
    }

    public function store(Request $request)
        {
            $project = Project::create([
                'user_id' => auth()->id(),
                'name' => $request->name,
                'description' => $request->description,
                'hourly_rate' => $request->hourly_rate,
            ]);

            return response()->json($project);
        }

       
}
