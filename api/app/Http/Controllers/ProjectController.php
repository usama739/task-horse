<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    public function index(){
        // if (auth()->user()->role !== 'admin') {
        //     abort(403, 'Unauthorized access.');
        // }

        $projects = Project::orderBy('created_at', 'desc')->get();;
        return response()->json($projects);
    }


    public function store(Request $request){
        $request->validate(['name' => 'required|string|max:255']);
        Project::create(['name' => $request->name]);

        return response()->json(['success' => 'Project added successfully'], 201);
    }


    public function update(Request $request, $id){
        $request->validate(['name' => 'required|string|max:255']);
        $Project = Project::findOrFail($id);
        $Project->update(['name' => $request->name]);

        return response()->json(['success' => 'Project updated successfully']);
    }

    
    public function destroy($id){
        $Project = Project::findOrFail($id);
        $Project->delete();

        return response()->json(['success' => 'Project deleted successfully']);
    }
}
