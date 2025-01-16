<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Ambil pengguna yang sedang login
            $user = JWTAuth::user();
            
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            // Menampilkan Data Tertentu
            $userData = $user->only(['name', 'email', 'date_of_birth', 'address', 'phone', 'photo']);

            // Tambahkan URL lengkap untuk foto jika ada
            $userData['photo'] = $user->photo ? asset('storage/' . $user->photo) : null;

            return response()->json($userData, 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Error', 'message' => $th->getMessage()], 500);
        }
    }


    /**
     * Update the specified resource in storage.
     */
     
     public function update(Request $request)
    {
        try {
            $user = JWTAuth::user();

            if (!$user) {
                return response()->json([
                    'message' => 'Data not found',
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|nullable|string|max:255',
                'email' => 'sometimes|nullable|email|max:255',
                'address' => 'sometimes|nullable|string|max:255',
                'phone' => 'sometimes|nullable|string|max:15',
                'date_of_birth' => 'sometimes|nullable|date',
                'photo' => 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $fieldsToUpdate = array_filter($request->only(['name', 'email', 'address', 'phone', 'date_of_birth']), function ($value) {
                return !is_null($value);
            });
            $user->update($fieldsToUpdate);

            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');

                $photoPath = $photo->store('photos', 'public');

                if ($user->photo) {
                    Storage::disk('public')->delete($user->photo);
                }

                $user->photo = $photoPath;
                $user->save();
            }

            return response()->json([
                'message' => 'Profile updated successfully',
                'management' => $user,
                'photo_url' => $user->photo ? asset('storage/' . $user->photo) : null,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Internal server error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
