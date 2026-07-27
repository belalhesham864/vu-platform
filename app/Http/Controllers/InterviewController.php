<?php

namespace App\Http\Controllers;

use App\Http\Requests\Interview\InterviewRequest;
use App\Http\Resources\Interview\InterviewResource;
use App\Models\Interview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InterviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $interviews = Interview::with('application', 'interviewer')->paginate();

        return apiResponse(200, 'Success', InterviewResource::collection($interviews));
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
    public function store(InterviewRequest $request)
    {
        $interviewer = Auth::user();
        $validated = $request->validated();

        $validated['interviewer_id'] = $interviewer->id;

        $interview = Interview::create($validated);

        return apiResponse(201, 'Interview created successfully', new InterviewResource($interview));
    }

    /**
     * Display the specified resource.
     */
    public function show(Interview $interview)
    {
        return apiResponse(200, 'Success', new InterviewResource($interview->load('application', 'interviewer')));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Interview $interview)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(InterviewRequest $request, Interview $interview)
    {
        $validated = $request->validated();

        $interview->update($validated);

        return apiResponse(200, 'Interview updated successfully', new InterviewResource($interview));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Interview $interview)
    {
        $interview->delete();

        return apiResponse(200, 'Interview deleted successfully', null);
    }
}
