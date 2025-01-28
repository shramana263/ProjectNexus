<?php

namespace App\Http\Controllers;

use App\Http\Requests\College\CollegeRequest;
use App\Models\College;
use Illuminate\Http\Request;

class CollegeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $colleges= College::all();
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
        if(College::where('name', $request->name)->exists()){
            return response()->json([
                'message' => 'College already exists'
            ],409);
        }
        $college= College::create($request->all());
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
        $college->delete();
        return response()->json([
            'message' => 'College deleted successfully'
        ],200);   
    }
}
