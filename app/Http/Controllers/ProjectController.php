<?php

namespace App\Http\Controllers;

use App\Http\Requests\Project\ProjectRequest;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::all();
        if (!$projects) {
            return response()->json([
                'message' => 'No projects found'
            ], 404);
        }
        return response()->json([
            'message' => 'Projects fetched successfully',
            'projects' => $projects
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProjectRequest $request)
    {

        if(auth()->user()->role !== 'faculty'){
            return response()->json([
                'message' => 'You are not authorized to create a project'
            ], 403);
        }

        $project=Project::create(array_merge(
            $request->validated(),
                [
                    'start_date'=>Carbon::now(),
                    'status'=>'pending',
                    'user_uuid'=>auth()->user()->uuid
                ]
        ));

        $project->faculty()->attach(auth()->user()->faculty->id);

        return response()->json([
            'message' => 'Project created successfully',
            "project" => $project
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProjectRequest $request, $id)
    {
        $project= Project::where('id',$id)->first();
        if($project==null){
            return response()->json([
                'message'=>'Project not found'
            ],404);
        }

        if($project->status!=='pending'){
            return response()->json([
                "message"=>"Project cannot be updated, contact to admin"
            ],403);
        }

        $project->name=$request->name;
        $project->description=$request->description;
        $project->budget=$request->budget;
        $project->save();

        return response()->json([
            "message"=>"Project successfully updated"
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        
        $project = Project::find($id);
        if (!$project) {
            return response()->json([
                'message' => 'Project not found'
            ], 404);
        }

        if($project->status!=='pending' && auth()->user()->role !== 'admin'){
            return response()->json([
                'message' => 'Project cannot be deleted, contact to admin'
            ], 403);
        }
        
        $project->delete();
        return response()->json([
            'message' => 'Project deleted successfully'
        ], 200);
    }

    public function updateProjectStatus(Request $request, $id)
    {
        $project = Project::find($id)->get();
        if (!$project) {
            return response()->json([
                'message' => 'Project not found'
            ], 404);
        }

        if(auth()->user()->role !== 'admin'){
            return response()->json([
                'message' => 'You are not authorized to update project status'
            ], 403);
        }

        $project->status = "ongoing";
        $project->save();
        return response()->json([
            'message' => 'Project approved',
            'project' => $project
        ], 200);
    }
    
}
