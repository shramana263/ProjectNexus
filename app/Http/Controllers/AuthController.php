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
use Ichtrojan\Otp\Otp;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    private $otp;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['sendOtp', 'register', 'login']]);
        $this->otp = new Otp;
    }

    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'role' => 'required|string',
            'contact_no' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        Session::put('user_data', $validator->validated());
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
        ]);
    }

    public function register(EmailVerificationRequest $request)
    {

        $otp1 = $request->otp;
        $email1 = $request->email;
        $fetchedUser = EmailVerification::where('otp', $request->otp)->first();
        if ($fetchedUser==null) {
            return response()->json([
                'message'=>'Invalid OTP given'
                // 'useremail'=>$fetchedUser->otp,
                // 'input'=>$request->otp
            ],401);
        }
        
        if ($request->otp != $fetchedUser->otp || $request->email != $fetchedUser->email) {
            return response()->json(['error' => 'Invalid OTP'], 401);
            
        }

        $user = User::create(array_merge(
            $fetchedUser->toArray(),
            [
                'email_verified_at' => now()
            ]
        ));

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ],201);

        // $user= User::where('email', $request->email)->first();
        // $user->update(['email_verified_at'=>now()]);
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
