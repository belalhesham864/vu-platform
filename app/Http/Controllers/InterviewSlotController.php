<?php

namespace App\Http\Controllers;

use App\Http\Requests\Slot\SlotRequest;
use App\Models\InterviewSlot;
use Illuminate\Http\Request;

class InterviewSlotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($interviewId)
    {
        $interviewSlots = InterviewSlot::where('interview_id', $interviewId)->get();
        return apiResponse(200, 'Success', $interviewSlots);
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
    public function store(SlotRequest $request)
    {
        $validatedData = $request->validated();

        $interviewSlot = InterviewSlot::create($validatedData);

        return apiResponse(201, 'Interview slot created successfully', $interviewSlot);
    }

    /**
     * Display the specified resource.
     */
    public function show(InterviewSlot $interviewSlot)
    {
        return apiResponse(200, 'Success', $interviewSlot);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InterviewSlot $interviewSlot)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SlotRequest $request, InterviewSlot $interviewSlot)
    {
        $validatedData = $request->validated();


        $interviewSlot->update($validatedData);

        return apiResponse(200, 'Interview slot updated successfully', $interviewSlot);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InterviewSlot $interviewSlot)
    {
        $interviewSlot->delete();

        return apiResponse(200, 'Interview slot deleted successfully');
    }
}
