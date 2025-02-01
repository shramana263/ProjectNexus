<?php

namespace App\Http\Controllers;

use App\Http\Requests\Project\CollaborationRequest;
use App\Models\Faculty;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CollaborationController extends Controller
{
    /**
     * Display all collaboration request to admin.
     */
    public function index()
    {
        $pendingPairs = DB::table('faculty_project')
            ->where('status', 'pending')
            ->get();

        if($pendingPairs==null){
            return response()->json([
                "message"=>"No pending Requests"
            ],404);
        }

        return response()->json([
            'message'=>"Pending requests fetched successfully",
            "data"=>$pendingPairs
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CollaborationRequest $request)
    {
        if (auth()->user()->role !== 'faculty') {
            return response()->json([
                'message' => 'You are not allowed to collaborate'
            ], 403);
        }

        $project = Project::find($request->project_id);
        if (!$project) {
            return response()->json([
                "message" => "project not found"
            ], 404);
        }

        $project->faculty()->attach(auth()->user()->faculty->id);

        return response()->json([
            'message' => 'Collaboration request sent'
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($collaboration)
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
    public function destroy($collaboration)
    {
        //
    }

    //Granting of the collaboration request by the admin
    public function grantRequest(Request $request) {
        $validator= Validator::make($request->all(),[
            'project_id'=>'required',
            'faculty_id'=>'required'
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors()->toJson(),400);

        }

        $data= DB::table('faculty_project')->where('faculty_id',$request->faculty_id)->where('project_id',$request->project_id)->first();

        if($data==null){
            return response()->json([
                "message"=>"Data not found"
            ],404);
        }
        $data->status= "approved";

        $data->save();

        return response()->json([
            "message"=>"Collaboration approved"
        ],200);
    }

    //view of collaboration data for a faculty
    public function viewCollaborationForFaculty(){
        // $projects = Project::where('user_uuid', auth()->user()->uuid)->get();

        // $projectIds = $projects->pluck('id')->toArray(); 
        
        // $faculties = Faculty::whereHas('projects', function ($query) use ($projectIds) {
        //     $query->whereIn('projects.id', $projectIds);
        // })->get(); 

        $data = Project::where('user_uuid', auth()->user()->uuid)->with('faculties')->get();

        return response()->json([
            "message"=>"Collaboration data fetched successfully",
            "data"=>$data
        ],200);
    }
}
