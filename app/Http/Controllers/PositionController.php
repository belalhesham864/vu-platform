<?php

namespace App\Http\Controllers;

use App\Http\Requests\Position\PositionRequest;
use App\Http\Resources\Position\PositionResource;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $positions = Position::all();

        return apiResponse(200, 'Positions retrieved successfully', $positions);
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
    public function store(PositionRequest $request)
    {
        $user = Auth::user();
        $data = $request->validated();
        $data['company_id'] = $user->company_id; // Assuming the user has a company_id attribute
        $data['approved_by'] = $user->id; // Set the approved_by field to the current user's ID

        $position = Position::create($data);

        return apiResponse(201, 'Position created successfully', new PositionResource($position));
    }

    /**
     * Display the specified resource.
     */
    public function show(Position $position)
    {
        return apiResponse(200, 'Position retrieved successfully', new PositionResource($position));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Position $position)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PositionRequest $request, Position $position)
    {
        $user = Auth::user();
        $data = $request->validated();
        $data['company_id'] = $user->company_id; // Assuming the user has a company_id attribute
        $data['approved_by'] = $user->id; // Set the approved_by field to the current user's ID

        $position->update($data);

        return apiResponse(200, 'Position updated successfully', new PositionResource($position));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Position $position)
    {
        $position->delete();

        return apiResponse(200, 'Position deleted successfully', null);
    }
}
