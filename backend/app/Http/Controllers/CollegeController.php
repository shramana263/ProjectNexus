<?php

namespace App\Http\Controllers;

use App\Http\Requests\College\CollegeRequest;
use App\Models\College;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CollegeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $colleges= College::all();
        if($colleges->count()==0){
            return response()->json([
                'message' => 'No colleges found'
            ],404);
        }
        return response()->json([
            'message' => 'Colleges fetched successfully',
            'colleges' => $colleges
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CollegeRequest $request)
    {
        $validated = $request->validated();
        if(College::where('name', $validated->name)->exists()){
            return response()->json([
                'message' => 'College already exists'
            ],409);
        }
        $college= College::create($validated->all());
        return response()->json([
            'message' => 'College created successfully',
            'college' => $college
        ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $college= College::findorFail($id);
        if(!$college){
            return response()->json([
                'message' => 'College not found'
            ],404);
        }
        return response()->json([
            'message' => 'College fetched successfully',
            'college' => $college
        ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CollegeRequest $request, $id)
    {
        $college= College::findorFail($id);
        if(!$college){
            return response()->json([
                'message' => 'College not found'
            ],404);
        }
        $college->update($request->all());
        return response()->json([
            'message' => 'College updated successfully',
            'college' => $college
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $college= College::findorFail($id);
        if(!$college){
            return response()->json([
                'message' => 'College not found'
            ],404);
        }
        $college->delete();
        return response()->json([
            'message' => 'College deleted successfully'
        ],200);   
    }
}
