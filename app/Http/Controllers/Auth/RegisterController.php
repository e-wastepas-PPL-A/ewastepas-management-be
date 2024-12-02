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
            // Validasi input
            $newUserData = $request->validated();
            $newUserData['password'] = Hash::make($newUserData['password']);

            // Buat pengguna baru
            $management = User::create($newUserData);

            // Kirim OTP melalui email
            $management->generateOtp();

            // Response yang sesuai dengan frontend
            return response()->json([
                'message' => 'Registrasi berhasil! Silakan verifikasi email Anda dengan OTP yang dikirim.',
                'email' => $management->email
            ], 201);

        } catch (\Exception $e) {
            // Kembalikan pesan error yang lebih sederhana untuk frontend
            return response()->json([
                'message' => 'Registrasi gagal! Coba lagi atau hubungi dukungan.',
                'error' => $e->getMessage() // Tambahkan ini jika butuh detail untuk debug
            ], 500);
        }
    }
}
