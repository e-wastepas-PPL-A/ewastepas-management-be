<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class ForgotPasswordController extends Controller
{
    public function sendOtp(Request $request)
    {
        // Validasi input email
        $request->validate([
            'email' => 'required|email|exists:management,email'
        ]);

        $user = User::where('email', $request->email)->first();

        // Panggil fungsi generateOtp di model User
        $user->generateOtp();

        return response()->json(['message' => 'OTP sent successfully.'], 200);
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|exists:management,email',
            'otp_code' => 'required|string',
            'password' => 'required|string|confirmed',
        ]);

        $user = User::where('email', $request->email)->first();

        // Periksa apakah OTP cocok dan belum kadaluwarsa
        if ($user->otp_code === $request->otp_code && $user->otp_expiry > Carbon::now()) {
            // Hash password baru sebelum menyimpannya
            $user->password = Hash::make($request->password);
            $user->otp_code = null;  // Hapus OTP setelah digunakan
            $user->otp_expiry = null; // Hapus waktu kadaluwarsa OTP
            $user->save();

            return response()->json(['message' => 'Password has been reset successfully'], 200);
        }
        return response()->json(['message' => 'Invalid OTP or OTP expired'], 400);
    }
}
