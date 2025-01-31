<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
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
                Rule::in(['admin', 'principal', 'faculty']), // Validate allowed roles
            ],
            'contact_no' => 'required|string',
            'password' => 'required|string|min:6',
            'college_id' => Rule::requiredIf(function () use ($request) {
                return $request->input('role') !== 'admin';
            }),
            'department'=>Rule::requiredIf(function()use($request){
                return $request->input('role')==='faculty';
            }),
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        $user= User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]      
        ));

        if($user->role=='faculty'){
            Faculty::create([
                'user_id'=>$user->id,
                'department'=>$request->department
            ]);
        }

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
    public function update(Request $request , $uuid)
    {
        $validator= Validator::make($request->all(),[
            'uuid' => 'required|string',
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users,email,',
            'role' => [
                'required',
                'string',
                Rule::in(['admin','princpal', 'faculty']), // Validate allowed roles
            ],
            'contact_no' => 'required|string',
            'password' => 'sometimes|string|min:6',
            'college_id' => Rule::requiredIf(function () use ($request) {
                return $request->input('role') !== 'admin';
            }),
            'department' => Rule::requiredIf(function () use ($request) {
                return $request->input('role') === 'faculty';
            }),
        ]);
        
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        
        $user= User::where('uuid',$uuid)->first();
        if(!$user){
            return response()->json([
                'message' => 'User not found'
            ],404);
        }

        $user->uuid= $validator->validated()['uuid'];
        $user->name= $validator->validated()['name'];
        $user->email= $validator->validated()['email'];
        $user->role= $validator->validated()['role'];
        $user->contact_no= $validator->validated()['contact_no'];
        if($validator->validated()['department']){
            $user->department= $validator->validated()['department'];
        }
        if($validator->validated()['college_id']){
            $user->department= $validator->validated()['college_id'];
        }

        $user->save();

        

        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user
        ],200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
