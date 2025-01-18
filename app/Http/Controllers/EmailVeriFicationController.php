<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\EmailVerificationRequest;
use App\Models\User;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;

class EmailVeriFicationController extends Controller
{
    private $otp;
    public function __construct()
    {
        $this->otp= new Otp;
    }
    public function email_verification(EmailVerificationRequest $request){
        $otp2= $this->otp->validate($request->email, $request->otp);
        if(!$otp2->status){
            return response()->json(['error'=>"email not valid"],401);
        }

        $user= User::where('email', $request->email)->first();
        $user->update(['email_verified_at'=>now()]);

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
