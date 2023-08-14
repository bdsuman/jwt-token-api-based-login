<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\UserController;


// API Routes
Route::post('/user-registration',[UserController::class,'UserRegistration']);
Route::post('/send-otp',[UserController::class,'SendOTPCode']);
Route::post('/verify-otp',[UserController::class,'VerifyOTP']);
Route::post('/user-login',[UserController::class,'UserLogin']);
Route::post('/forgot-password',[UserController::class,'SendPassword']);


Route::group(['prefix' => '', 'middleware' => ['jwt-auth']], function() {
    Route::get('/user-profile',[UserController::class,'UserProfile']);
    Route::post('/user-update',[UserController::class,'UpdateProfile']);
    
    // Customer API
    Route::post("/create-customer",[CustomerController::class,'CustomerCreate']);
    Route::get("/list-customer",[CustomerController::class,'CustomerList']);
    Route::post("/delete-customer",[CustomerController::class,'CustomerDelete']);
    Route::post("/update-customer",[CustomerController::class,'CustomerUpdate']);
    Route::post("/customer-by-id",[CustomerController::class,'CustomerByID']);


});
