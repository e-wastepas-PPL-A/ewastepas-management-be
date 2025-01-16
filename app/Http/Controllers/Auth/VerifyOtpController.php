<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use Illuminate\Routing\Route;

class VerifyOtpController extends Controller
{
    public function verifyOtp(Request $request): JsonResponse
    {
        try {
            $route = $request->path();

            $purpose = '';
            if (strpos($route, 'register') !== false) {
                $purpose = 'registration';
            } elseif (strpos($route, 'forgot-password') !== false) {
                $purpose = 'password_reset';
            }

            $request->validate([
                'otp_code' => 'required|string|max:6',
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json(['error' => 'User not found.'], 404);
            }

            if ($user->otp_code === $request->otp_code && Carbon::now()->lessThanOrEqualTo($user->otp_expiry)) {
                if ($purpose === 'registration') {
                    // Jika tujuan adalah registrasi
                    $user->update([
                        'is_verified' => 1,
                        'otp_code' => null,
                        'otp_expiry' => null,
                    ]);
                    return response()->json([
                        'success' => true,
                        'message' => 'OTP verified successfully for registration.'
                    ], 200);
                }

                if ($purpose === 'password_reset') {
                    // Jika tujuan adalah reset password
                    $user->update([
                        'otp_code' => null,
                        'otp_expiry' => null,
                    ]);
                    return response()->json([
                        'success' => true,
                        'message' => 'OTP verified successfully for password reset.'
                    ], 200);
                }
            }

            return response()->json(['error' => 'Invalid or expired OTP.'], 400);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal Server Error', 'message' => $e->getMessage()], 500);
        }
    }
}