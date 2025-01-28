<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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
                "users"=> User::where('role','faculty')->where('college',$user->college_id)->get()
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'role' => [
                'required',
                'string',
                Rule::in(['admin', 'student', 'faculty']), // Validate allowed roles
            ],
            'contact_no' => 'required|string',
            'password' => 'required|string|min:6',
            'college_id' => Rule::requiredIf(function () use ($request) {
                return $request->input('role') !== 'admin';
            }),
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        $user= User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]      
        ));

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ],201);
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
    public function update(Request $request , string $uuid)
    {
        $validator= Validator::make($request->all(),[
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users,email,',
            'role' => [
                'required',
                'string',
                Rule::in(['admin', 'princpal', 'faculty']), // Validate allowed roles
            ],
            'contact_no' => 'required|string',
            'password' => 'required|string|min:6',
            'college_id' => Rule::requiredIf(function () use ($request) {
                return $request->input('role') !== 'admin';
            }),
        ]);
        
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        
        $user= User::where('uuid',$uuid)->first();
        $user->update($validator->all());

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
