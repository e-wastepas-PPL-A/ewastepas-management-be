<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function login(LoginRequest $request):JsonResponse
    {
        $credentials = $request->only('email', 'password');
    
        if (auth()->attempt($credentials)) {
            $user = Auth::user(); // Ensure the user is authenticated
    
            if ($user) {
                $success['token'] = $user->createToken($request->userAgent())->plainTextToken;
                $success['name'] = $user->name;
                $success['email'] = $user->email;
                $success['success'] = true;
    
                return response()->json($success, 200);
            }
        }
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function logout(): JsonResponse
    {
        auth()->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    // Redirect ke Google
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
                'is_admin' => false, // Nilai default
                'date_of_birth' => null, // Isi nilai default jika tidak ada
                'address' => null,
                'phone' => null,
                'photo' => $googleUser->getAvatar(), // Menyimpan URL avatar dari Google
            ]
        );

        // Buat token untuk autentikasi API
        $token = $user->createToken('GoogleLoginToken')->plainTextToken;

        // Mengembalikan respons JSON dengan token dan data pengguna
        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_verified' => $user->is_verified,
                'is_admin' => $user->is_admin,
                'date_of_birth' => $user->date_of_birth,
                'address' => $user->address,
                'phone' => $user->phone,
                'photo' => $user->photo,
            ]
        ], 200);

    } catch (\Exception $e) {
        return response()->json(['error' => 'Unable to login with Google.'], 500);
    }
}

}
