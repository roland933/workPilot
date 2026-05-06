<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Project;

class ProjectController
{
   public function index(Request $request)
    {   
        
       $query = Project::where('user_id', auth()->id());

   
        if ($request->search) {
                $query->where('name', 'like', '%' . $request->search . '%');
          }

  
        return $query->orderBy('id', 'desc')->paginate(3);
    }

    public function store(Request $request)
        {

             $validated = $request->validate([
                'name' => 'required|string|max:255',
                'hourly_rate' => 'required|numeric|min:0',
                 ]);

            $project = Project::create([
                ...$validated,    
                'user_id' => auth()->id(),
                'name' => $request->name,
                'description' => $request->description,
                'hourly_rate' => $request->hourly_rate,
            ]);

            return response()->json($project);
        }

    public function update(Request $request, $id)
     {
               $validated = $request->validate([
                        'name' => 'required|string|max:255',
                        'hourly_rate' => 'required|numeric|min:0',
                 ]);


                $project = Project::where('user_id', auth()->id())
                    ->findOrFail($id);

                $project->update([
                       ...$validated,    
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
