<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\FacultyIdController;
use App\Http\Requests\Auth\EmailVerificationRequest;
use App\Models\EmailVerification;
use App\Notifications\EmailVerificationNotification;
use App\Notifications\ResetPasswordNotification;
use Ichtrojan\Otp\Otp;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    private $otp;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['sendOtp', 'register', 'login','forget_password']]);
        $this->otp = new Otp;
    }

    public function sendOtp(Request $request)
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

        // Session::put('user_data', $validator->validated());
        // $user= $request;

        // $user= User::create(array_merge(
        //     $validator->validated(),
        //     ['password' => bcrypt($request->password)]      
        // ));
        // notify(new EmailVerificationNotification());
        Notification::route('mail', $request->email)->notify(new EmailVerificationNotification($request));

        // if($user->role == 'faculty'){
        //     $facultyIdController = new FacultyIdController();
        //     $facultyIdController->store($user->uuid);
        // }

        // return response()->json([
        //     'message' => 'User successfully registered',
        //     'user' => $user
        // ],201);

        return response()->json([
            'message' => 'OTP sent to your email'
        ],200);
    }

    public function register(EmailVerificationRequest $request)
    {

        $fetchedUser = EmailVerification::where('otp', $request->otp)->first();
        if ($fetchedUser==null) {
            return response()->json([
                'message'=>'Invalid OTP given'
            ],401);
        }
        
        if ($request->otp != $fetchedUser->otp || $request->email != $fetchedUser->email) {
            return response()->json(['error' => 'Invalid OTP'], 401);
            
        }

        if($fetchedUser->expires_at < now()){
            return response()->json(['error'=>'OTP has expired, request for new OTP']);
        }

        $user = User::create(array_merge(
            $fetchedUser->toArray(),
            [
                'email_verified_at' => now()
            ]
        ));
        // if($user->role == 'faculty'){
        //     $facultyIdController = new FacultyIdController();
        //     $facultyIdController->store($user->uuid);
        // }

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ],201);

        // $user= User::where('email', $request->email)->first();
        // $user->update(['email_verified_at'=>now()]);
    }


    public function forget_password(Request $request){
        $validator= Validator::make($request->all(),[
            'email'=>'required|string|email'
        ]);

        if($validator->fails()){
            return response()->json($validator->error(),422);
        }

        $user= User::where('email',$request->email)->first();

        if($user== null){
            return response()->json([
                "message"=>"Account does not exist, provide a valid email"
            ]);
        }

        Notification::route('mail', $request->email)->notify(new ResetPasswordNotification($user));

        return response()->json([
            "message"=>"OTP has been sent to your email"
        ],200);


    }



    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (!$token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->createNewToken($token);
    }

    public function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60,
            'user' => auth()->user()
        ]);
    }

    public function user()
    {
        return response()->json(auth()->user());
    }

    public function logout()
    {
        auth('api')->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }
}
