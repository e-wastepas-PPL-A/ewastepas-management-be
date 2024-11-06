<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;


class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::find();

        // Pastikan pengguna ditemukan
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user, 200);
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
    public function update(Request $request)
    {
        $user = User::find();

        // Pastikan pengguna ditemukan
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Validasi input yang dikirim
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
        ]);

        // Update data pengguna
        $user->update($validated);

        return response()->json(['message' => 'Profile updated successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
