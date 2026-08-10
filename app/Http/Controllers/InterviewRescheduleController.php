<?php

namespace App\Http\Controllers;

use App\Http\Requests\Reschedules\ReschedulesRequest;
use App\Models\InterviewReschedule;
use App\Models\InterviewSlot;
use Illuminate\Http\Request;

class InterviewRescheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($slotId)
    {
        $reschedules = InterviewReschedule::where('interview_slot_id', $slotId)->get();
        return apiResponse(200, 'Success', $reschedules);
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
    public function store(ReschedulesRequest $request)
    {
        $validatedData = $request->validated();

        $slot = InterviewSlot::findOrFail($request->interview_slot_id);

        $reschedule = InterviewReschedule::create([
            'interview_slot_id' => $validatedData['interview_slot_id'],
            'date' => $validatedData['date'],
            'old_start_time' => $slot->start_time,
            'old_end_time' => $slot->end_time,
            'new_start_time' => $validatedData['new_start_time'],
            'new_end_time' => $validatedData['new_end_time'],
            'reason' => $validatedData['reason'],
            'requested_by' => $validatedData['requested_by']
        ]);

        return apiResponse(201, 'Interview reschedule created successfully', $reschedule);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $interviewReschedule = InterviewReschedule::findOrFail($id);
        return apiResponse(200, 'Success', $interviewReschedule);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InterviewReschedule $interviewReschedule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ReschedulesRequest $request, InterviewReschedule $interviewReschedule)
    {
        $validatedData = $request->validated();

        $slot = InterviewSlot::findOrFail($request->interview_slot_id ?? $interviewReschedule->interview_slot_id);

        $interviewReschedule->update([
            'interview_slot_id' => $validatedData['interview_slot_id'] ?? $interviewReschedule->interview_slot_id,
            'date' => $validatedData['date'] ?? $interviewReschedule->date,
            'old_start_time' => $slot->start_time,
            'old_end_time' => $slot->end_time,
            'new_start_time' => $validatedData['new_start_time'] ?? $interviewReschedule->new_start_time,
            'new_end_time' => $validatedData['new_end_time'] ?? $interviewReschedule->new_end_time,
            'reason' => $validatedData['reason'] ?? $interviewReschedule->reason,
            'status' => $validatedData['status'] ?? $interviewReschedule->status,
            'requested_by' => $validatedData['requested_by'] ?? $interviewReschedule->requested_by
        ]);

        return apiResponse(200, 'Interview reschedule updated successfully', $interviewReschedule);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InterviewReschedule $interviewReschedule)
    {
        $interviewReschedule->delete();

        return apiResponse(200, 'Interview reschedule deleted successfully');
    }
}
