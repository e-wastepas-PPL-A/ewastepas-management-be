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
        // Mendapatkan kredensial dari request
        $credentials = $request->only('email', 'password');

        try {
            // Mencoba untuk membuat token
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Invalid email or password.'], 401);
            }

            $user = Auth::user();

            // Cek apakah pengguna sudah diverifikasi
            if (!$user->is_verified) {
                // Logout pengguna jika belum diverifikasi
                JWTAuth::invalidate($token);
                return response()->json([
                    'error' => 'Please verify your email address before logging in.',
                    'resend_verification' => true,
                ], 401);
            }

            // Siapkan data respons
            $success = [
                'token' => $token,
                'name' => $user->name,
                'email' => $user->email,
                'photo' => $user->photo ? asset('storage/' . $user->photo) : null,
                'success' => true,
            ];

            return response()->json($success, 200);
        } catch (JWTException $e) {
            // Respons jika terjadi kesalahan saat membuat token
            return response()->json(['error' => 'Could not create token.'], 500);
        }
    }
    
    public function logout(Request $request): JsonResponse
    {
    try {
        // Ambil token dari header Authorization
        $token = $request->bearerToken();
        if (!$token) {
            return Response()->json(['message' => 'Eror'], 400);// Token tidak ditemukan
        }

        // Menghapus token yang ada
        $request->user()->currentAccessToken()->delete();

            return response()->json(['message' => 'Successfully logged out.'], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Unable to logout.'], 500); // Menangani error lainnya
        }
    }



    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    // Callback dari Google
    public function handleGoogleCallback()
    {
        try {
            // Mengambil data pengguna dari Google
            $googleUser = Socialite::driver('google')->stateless()->user();
            // Sinkronisasi data pengguna Google ke dalam model User
            $user = User::updateOrCreate(
                [
                    'email' => $googleUser->getEmail(),
                ],
                [
                    'name' => $googleUser->getName(),
                    'password' => bcrypt(Str::random(24)), // Menggunakan password acak untuk keamanan
                    'google_id' => $googleUser->getId(),
                    'is_verified' => true, // Otomatis dianggap terverifikasi jika login dari Google
                    'is_admin' => false,
                    'date_of_birth' => null,
                    'address' => null,
                    'phone' => null,
                    'photo' => $googleUser->getAvatar(),
                ]
            );

            // Buat token untuk autentikasi API
            $token = $user->createToken('GoogleLoginToken')->plainTextToken;

            // Mengembalikan respons JSON dengan token dan data pengguna
            return redirect(env('FRONTEND_URL'));

        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to login with Google.'], 500);
        }
    }
}
