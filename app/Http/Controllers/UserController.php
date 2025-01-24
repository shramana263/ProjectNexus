<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function __construct()
    {
        // $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     */
    public function fetchView()
    {
        $user= auth()->user();
        if($user->role == 'admin'){
            return response()->json([
                'message' => 'Data for admin fetched successfully',
                'users' => User::where('role','principle')->orWhere('role','faculty')->get()
            ],200);
        }
        else if($user->role=='principle'){
            return response()->json([
                "message"=> "Data for principle fetched successfully",
                "users"=> User::where('role','faculty')->where('college',$user->college)->get()
            ],200);
        }
        else{
            return response()->json([
                "message"=> "Data for faculty fetched successfully",
                "user"=> User::where('uuid',$user->uuid)->first()
            ]);
        }
    }

    public function collaboration(){
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
