<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\ResetPassword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class ResetPasswordController extends Controller
{
    private $otp;

    public function __construct() {}

    public function reset_password(ResetPasswordRequest $request)
    {

        $fetchedUser = ResetPassword::where('otp', $request->otp)->first();
        if ($fetchedUser == null) {
            return response()->json([
                'message' => 'Invalid OTP'
            ], 401);
        }

        if ($request->email != $fetchedUser->email) {
            return response()->json([
                'message' => 'OTP not valid'
            ], 401);
        }

        if ($fetchedUser->expires_at < now()) {
            return response()->json([
                'message' => 'OTP has expired, request for new OTP'
            ]);
        }

        // $storedOtp = // Retrieve from storage
        // $enteredOtp = $request->input('otp');
        $user= User::where('email',$request->email)->first();

        try {
            if (! $token = JWTAuth::fromUser($user)) { // Generate token for the user
                return response()->json(['error' => 'Could not create token'], 500); // Internal Server Error
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500); // Internal Server Error
        }

        // Delete the OTP from storage
        // ...

        return response()->json(['token' => $token]);
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
    public function show(ResetPassword $resetPassword)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ResetPassword $resetPassword)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ResetPassword $resetPassword)
    {
        //
    }
}
