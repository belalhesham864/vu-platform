<?php

namespace App\Http\Controllers;

use App\Models\PositionStage;
use Illuminate\Http\Request;

class PositionStageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stages = PositionStage::with('position')->paginate();

        return apiResponse(200, 'Success', $stages);
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
    public function store(Request $request)
    {
        $validated = $request->validate([
            'position_id' => 'required|exists:positions,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'required|integer|min:1',
        ]);

        $stage = PositionStage::create($validated);

        return apiResponse(201, 'Stage created successfully', $stage);
    }

    /**
     * Display the specified resource.
     */
    public function show(PositionStage $positionStage)
    {
        return apiResponse(200, 'Success', $positionStage->load('position'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PositionStage $positionStage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PositionStage $positionStage)
    {
        $validated = $request->validate([
            'position_id' => 'sometimes|exists:positions,id',
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'order' => 'sometimes|integer|min:1',
        ]);

        $positionStage->update($validated);

        return apiResponse(200, 'Stage updated successfully', $positionStage);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PositionStage $positionStage)
    {
        $positionStage->delete();

        return apiResponse(200, 'Stage deleted successfully');
    }
}
