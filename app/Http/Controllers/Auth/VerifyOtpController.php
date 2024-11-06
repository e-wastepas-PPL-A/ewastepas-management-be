<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class VerifyOtpController extends Controller
{
    public function verifyOtp(Request $request)
{
    try {
        $request->validate([
            'email' => 'required|email',
            'otp_code' => 'required|string|max:6',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        // Cek apakah OTP sesuai dan masih berlaku
        if ($user->otp_code === $request->otp_code && Carbon::now()->lessThanOrEqualTo($user->otp_expiry)) {
            $user->update([
                'is_verified' => 1,
                'otp_code' => null,
                'otp_expiry' => null,
            ]);

            return response()->json(['message' => 'OTP verified successfully.']);
        }

        return response()->json(['error' => 'Invalid or expired OTP.'], 400);
    } catch (\Exception $e) {
        // Log error dan tampilkan pesan debugging
        Log::error($e->getMessage());
        return response()->json(['error' => 'Internal Server Error', 'message' => $e->getMessage()], 500);
    }
}

}
