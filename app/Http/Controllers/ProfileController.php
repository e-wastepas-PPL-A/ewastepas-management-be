<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;


class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(){
    try {
        // Ambil pengguna yang sedang login
        $user = auth()->user();
        // Menampilkan Data Tertentu
        $userData = $user->only(['management_id', 'name', 'email', 'date_of_birth', 'address', 'phone', 'photo']);
        return response()->json($userData, 200);
    } catch (\Throwable $th) {
            return response()->json(['error' => 'Error'], 500);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        
    }
    
    /**
     * Update the specified resource in storage.
     */
    // app/Http/Controllers/ProfileController.php
    public function update(Request $request)
    {
        try {
            // Ambil pengguna yang sedang login
            $user = auth()->user();

            // Validasi input yang dikirim
            $validated = $request->validate([
                'name' => 'nullable|string|max:255',
                'email' => 'required|email|max:255|unique:management,email,' . $user->management_id . ',management_id', // tetapkan primary key sebagai management_id
                'date_of_birth' => 'required|date',
                'address' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:15',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            // Update data pengguna
            $user->update($validated);

            return response()->json(['message' => 'Profile updated successfully'], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Tangani error validasi
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            // Tangani error lainnya 
            return response()->json(['error' => 'An error occurred while updating the profile.'], 500);
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
