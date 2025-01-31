<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SkillController extends Controller
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
    public function store(Request $request)
    {
        $validatedSkill= Validator::make($request->all(),[
            'name'=>'required|string'
        ]);
        $skill= Skill::create($validatedSkill->validated());
        return response()->json([
            "message"=>"Skill created successfully",
            "skill"=>$skill
        ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Skill $skill)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $skill=Skill::find($id);
        $skill->update($request->all());
        return response()->json([
            "message"=>"Skill updated successfully",
            "skill"=>$skill
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Skill $skill)
    {
        //
    }
}
