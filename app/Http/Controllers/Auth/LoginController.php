<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Helpers\ResponseJson;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Invalid email or password.'], 401);
            }

            $user = Auth::user();

            if (!$user->is_verified) {
                JWTAuth::invalidate($token);
                return response()->json([
                    'error' => 'Please verify your email address before logging in.',
                    'resend_verification' => true,
                ], 401);
            }

            $success = [
                'token' => $token,
                'name' => $user->name,
                'email' => $user->email,
                'photo' => $user->photo ? asset('storage/' . $user->photo) : null,
                'success' => true,
            ];

            return response()->json($success, 200);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token.'], 500);
        }
    }
    
    public function logout(Request $request): JsonResponse
    {
    try {
        $token = $request->bearerToken();
        if (!$token) {
            return Response()->json(['message' => 'Eror'], 400);
        }

        $request->user()->currentAccessToken()->delete();

            return response()->json(['message' => 'Successfully logged out.'], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Unable to logout.'], 500);
        }
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            $user = User::updateOrCreate(
                [
                    'email' => $googleUser->getEmail(),
                ],
                [
                    'name' => $googleUser->getName(),
                    'password' => bcrypt(Str::random(24)),
                    'google_id' => $googleUser->getId(),
                    'is_verified' => true, 
                    'is_admin' => false,
                    'date_of_birth' => null,
                    'address' => null,
                    'phone' => null,
                    'photo' => $googleUser->getAvatar(),
                ]
            );
            $token = $user->createToken('GoogleLoginToken')->plainTextToken;
            return redirect(env('FRONTEND_URL'));

        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to login with Google.'], 500);
        }
    }
}
