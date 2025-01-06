<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class ForgotPasswordController extends Controller
{
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'Email not found'], 404);
        }

        $user->generateOtp();
        $payload = [
            'email' => $user->email,
            'otp_code' => $user->otp_code,
            'otp_expiry' => now()->addMinutes(10)->timestamp,
        ];
    
        $token = JWTAuth::fromUser($user, $payload);
        return response()->json([
            'message' => 'Otp terkirim',
            'token' => $token,
        ]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $token = $request->bearerToken();
            
            if (!$token) {
                return response()->json(['error' => 'Token not provided.'], 400);
            }

            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json(['error' => 'User not found.'], 404);
            }

            if ($request->password !== $request->password_confirmation) {
                return response()->json(['error' => 'Password and confirmation do not match.'], 400);
            }

            $user->update([
                'password' => bcrypt($request->password),
            ]);

            return response()->json(['message' => 'Password updated successfully.'], 200);

        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['error' => 'Token has expired.'], 400);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['error' => 'Invalid token.'], 400);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['error' => 'Token is required.'], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal Server Error', 'message' => $e->getMessage()], 500);
        }
    }
}
