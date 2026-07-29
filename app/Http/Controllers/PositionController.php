<?php

namespace App\Http\Controllers;

use App\Http\Requests\Position\PositionRequest;
use App\Http\Resources\Position\PositionResource;
use App\Models\Position;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Facades\Auth;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!auth()->user()->can('view_jobs')) {
            return apiResponse(403 , 'You Can Not View Any Job');
        }

        $positions = QueryBuilder::for(Position::class)
            ->allowedFilters('title', 'description')
            ->allowedSorts('created_at', 'updated_at')
            ->paginate();

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
        if (!auth()->user()->can('create_job')) {
            return apiResponse(403 , 'You Can Not Create Job');
        }

        $user = Auth::user();
        $data = $request->validated();
        $data['company_id'] = $user->company_id;
        $data['approved_by'] = $user->id;

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
        if (!auth()->user()->can('edit_job')) {
            return apiResponse(403 , 'You Can Not Edit Job');
        }

        $user = Auth::user();
        $data = $request->validated();
        $data['company_id'] = $user->company_id;
        $data['approved_by'] = $user->id;

        $position->update($data);

        return apiResponse(200, 'Position updated successfully', new PositionResource($position));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Position $position)
    {
        if (!auth()->user()->can('delete_job')) {
            return apiResponse(403 , 'You Can Not Delete Job');
        }

        $position->delete();

        return apiResponse(200, 'Position deleted successfully', null);
    }
}
