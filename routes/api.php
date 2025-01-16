<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\VerifyOtpController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WasteTypeController;

Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [LoginController::class, 'login']);
Route::post('/register/verify-otp', [VerifyOtpController::class, 'verifyOtp']);
Route::post('/forgot-password/verify-otp', [VerifyOtpController::class, 'verifyOtp']);
Route::post('send-otp', [VerifyOtpController::class, 'sendOtp']);
Route::post('forgot-password', [ForgotPasswordController::class, 'forgotpassword']);
Route::post('change-password', [ForgotPasswordController::class, 'changepassword']);

Route::get('/auth/google', [LoginController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [LoginController::class, 'handleGoogleCallback']);

Route::middleware(['auth:api', 'is_verified'])->group(function () {
    Route::post('logout', [LoginController::class, 'logout']);
    Route::get('profile', [ProfileController::class, 'index']);
    Route::post('profile-update', [ProfileController::class, 'update']);
    Route::get('categories', [WasteTypeController::class, 'index']);
});



