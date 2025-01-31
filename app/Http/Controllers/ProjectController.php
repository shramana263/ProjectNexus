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
        //
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
                    'status'=>'pending'
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
    public function update(Request $request, Project $project)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        //
    }
}
