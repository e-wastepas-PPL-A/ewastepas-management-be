<?php

namespace App\Http\Controllers;

use App\Models\Waste;
use App\Http\Requests\StoreWasteRequest;
use App\Http\Requests\UpdateWasteRequest;

class WasteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(StoreWasteRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Waste $waste)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Waste $waste)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWasteRequest $request, Waste $waste)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Waste $waste)
    {
        //
    }
}
