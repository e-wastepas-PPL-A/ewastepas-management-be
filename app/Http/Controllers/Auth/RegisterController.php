<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $newuser = $request->validated();
            $newuser['password'] = Hash::make($newuser['password']);
            $user = User::create($newuser);

            // Send OTP email
            $user->generateOtp();

            return response()->json(['message' => 'Registration successful! Please verify your email with the OTP sent to your email.','email' => $user->email], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Registration failed!','error' => $e->getMessage()], 500);
        }
    }
}
