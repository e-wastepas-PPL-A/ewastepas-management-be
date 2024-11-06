<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Hash;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Mail\OtpMail;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
{
    $newuser = $request->validated();
    $newuser['password'] = Hash::make($newuser['password']);

    // Generate OTP
    $otp = random_int(100000, 999999);
    $newuser['otp_code'] = $otp;
    $newuser['otp_expiry'] = Carbon::now()->addMinutes(10);

    $user = User::create($newuser);

    // Send OTP email
    Mail::to($user->email)->send(new OtpMail($user->name, $otp));

    return response()->json([
        'message' => 'Registration successful! Please verify your email with the OTP sent to your email.',
        'email' => $user->email
    ], 201);
}
}
