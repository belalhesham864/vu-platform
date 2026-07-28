<?php

namespace App\Http\Controllers;

use App\Http\Requests\Stage\StageRequest;
use App\Http\Resources\Stage\StageResource;
use App\Models\PositionStage;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class PositionStageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stages = PositionStage::with('position')->paginate();

        return apiResponse(200, 'Success', StageResource::collection($stages));
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
    public function store(StageRequest $request)
    {
        $validated = $request->validated();

        $stage = PositionStage::create($validated);

        return apiResponse(201, 'Stage created successfully', $stage);
    }

    /**
     * Display the specified resource.
     */
    public function show(PositionStage $positionStage)
    {
        return apiResponse(200, 'Success', new StageResource($positionStage));
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
    public function update(StageRequest $request, PositionStage $positionStage)
    {
        $validated = $request->validated();

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
