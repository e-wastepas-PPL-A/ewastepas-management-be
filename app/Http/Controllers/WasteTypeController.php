<?php

namespace App\Http\Controllers;

use App\Models\WasteType;
use App\Http\Requests\StoreWasteTypeRequest;
use App\Http\Requests\UpdateWasteTypeRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class WasteTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $management = Auth::user();

        if (!$management) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $wasteTypes = WasteType::all();

        return response()->json($wasteTypes, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWasteTypeRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($wasteTypeId): JsonResponse
    {
         // Cari kategori sampah berdasarkan ID
         $management = Auth::user();

        if (!$management) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        
         $wasteType = WasteType::find($wasteTypeId);

         if (!$wasteType) {
             return response()->json(['message' => 'Kategori sampah tidak ditemukan'], 404);
         }
 
         // Ambil jenis sampah yang terkait dengan kategori
         $wastes = $wasteType->wastes;
 
         return response()->json([
             'waste_type' => $wasteType,
             'wastes' => $wastes,
         ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WasteType $wasteType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWasteTypeRequest $request, WasteType $wasteType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WasteType $wasteType)
    {
        //
    }
}
