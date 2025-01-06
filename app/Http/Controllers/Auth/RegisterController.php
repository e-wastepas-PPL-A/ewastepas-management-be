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
            $newUserData = $request->validated();
            $newUserData['password'] = Hash::make($newUserData['password']);

            $management = User::create($newUserData);

            $management->generateOtp();

            return response()->json([
                'message' => 'Registrasi berhasil! Silakan verifikasi email Anda dengan OTP yang dikirim.',
                'email' => $management->email
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Registrasi gagal! Coba lagi atau hubungi dukungan.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
