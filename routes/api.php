<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CollegeController;
use App\Http\Controllers\EmailVeriFicationController;
use App\Http\Controllers\FilterController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware'=>'api','prefix'=>'auth'],function(){
    
    Route::post('/send-otp',[AuthController::class,'sendOtp']);
    Route::post('/register',[AuthController::class,'register']);
    Route::post('/login',[AuthController::class,'login']);
    Route::post('/logout',[AuthController::class,'logout']);
    Route::get('/user',[AuthController::class,'user']);
    Route::post('/forget-password',[AuthController::class,'forget_password']);
    Route::post('/reset-password',[ResetPasswordController::class,'reset_password']);
    Route::post('/post/project',[ProjectController::class,'store']);
});

Route::group(['middleware'=>['api','auth','admin'], 'prefix'=>'admin'],function(){
    Route::post('/add-college',[CollegeController::class,'store']);
    Route::post('/add-user',[UserController::class,'store']);
    Route::put('/update-user',[UserController::class,'update']);
    // Route::get('/college/{id}',[CollegeController::class,'show']);
    Route::put('/update/college/{id}',[CollegeController::class,'update']);
    Route::delete('/delete/college/{id}',[CollegeController::class,'destroy']);
    Route::get('/filter-by-college/{id}',[FilterController::class,'filterByCollege']);
    Route::get('/filter-by-role/{role}',[FilterController::class,'filterByRole']);
    Route::post('/add-skill',[SkillController::class,'store']);
    Route::put('/update-skill/{id}',[SkillController::class,'update']);
});

Route::group(['middleware'=>'api','prefix'=>'authorized'],function(){
    Route::get('/all-data',[UserController::class,'fetchView']);
    Route::get('/all-colleges',[CollegeController::class,'index']);
    Route::get('/college/{id}',[CollegeController::class,'show']);
    Route::get('/all-skills',[SkillController::class,'index']);
    Route::post('/update-data',[AuthController::class,'update']);
});