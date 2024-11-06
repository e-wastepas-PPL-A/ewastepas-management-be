<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\VerifyOtpController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Rute untuk mendaftar (register) dan login, di luar auth middleware
Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [LoginController::class, 'login']);
Route::post('verify-otp', [VerifyOtpController::class, 'verifyOtp']);
Route::post('forgot-password', [ForgotPasswordController::class, 'sendOtp']);
Route::post('reset-password', [ForgotPasswordController::class, 'resetPassword']);


Route::get('/auth/google', [LoginController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [LoginController::class, 'handleGoogleCallback']);


// Rute yang membutuhkan autentikasi dan verifikasi
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
