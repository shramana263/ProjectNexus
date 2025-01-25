<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FilterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function filterByCollege($id){
        $users= User::where('college',$id)->get();
        return response()->json([
            'message' => 'Data fetched successfully',
            'users' => $users
        ],200);
    }

    // public function filterBySkills(Request $request){
    //     $users= User::where('skills',$request)->get();
    //     return response()->json([
    //         'message' => 'Data fetched successfully',
    //         'users' => $users
    //     ],200);
    // }


    public function filterByRole(Request $request){
        $users = User::where('role',$request)->get();
        return response()->json([
            'message' => 'Data fetched successfully',
            'users' => $users
        ],200);
    }


    
}
