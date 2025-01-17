<?php

namespace App\Http\Controllers;

use App\Models\FacultyId;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class FacultyIdController extends Controller
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

     function generateUniqueFacultyId() {
        $length = 12; // Adjust length as needed
        do {
            $id = 'FAC-' . Str::random($length - 4); // Generates a random string of specified length
        } while (FacultyId::where('faculty_id', $id)->exists());
    
        return $id;
    }

    public function store($id)
    {
        $facultyId = new FacultyId();
        $facultyId->faculty_id = $this->generateUniqueFacultyId();
        $facultyId->user_id = $id;
        $facultyId->save();
    }

    /**
     * Display the specified resource.
     */
    public function show(FacultyId $facultyId)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FacultyId $facultyId)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FacultyId $facultyId)
    {
        //
    }
}
