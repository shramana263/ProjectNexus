<?php

namespace App\Http\Controllers;

use App\Http\Requests\Project\CollaborationRequest;
use App\Models\Project;
use Illuminate\Http\Request;

class CollaborationController extends Controller
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
    public function store(CollaborationRequest $request)
    {
        if(auth()->user()->role!=='faculty'){
            return response()->json([
                'message'=>'You are not allowed to collaborate'
            ],403);
        }

        $project= Project::find($request->project_id);
        if(!$project){
            return response()->json([
                "message"=>"project not found"
            ],404);
        }

        $project->faculty()->attach(auth()->user()->faculty->id);

        return response()->json([
            'message'=>'Collaboration request sent'
        ],200);
    }

    /**
     * Display the specified resource.
     */
    public function show( $collaboration)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $collaboration)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $collaboration)
    {
        //
    }
}
