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

    public function update(Request $request, $id)
     {
                $project = Project::where('user_id', auth()->id())
                    ->findOrFail($id);

                $project->update([
                    'name' => $request->name,
                    'hourly_rate' => $request->hourly_rate,
                ]);

                return response()->json($project);
      }

      public function destroy($id)
        {
                $project = Project::where('user_id', auth()->id())
                    ->findOrFail($id);

                $project->delete();

                return response()->json(['message' => 'Deleted']);
        }

       
}
