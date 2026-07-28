<?php

namespace App\Http\Controllers;

use App\Http\Requests\Reschedules\ReschedulesRequest;
use App\Models\InterviewReschedule;
use App\Models\InterviewSlot;
use Illuminate\Http\Request;

class InterviewReschedulesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($slotId)
    {
        $reschedule = InterviewReschedule::where('interview_slot_id', $slotId)->paginate();

        return apiResponse(200, "Reschedules retrieved successfully", $reschedule);
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
        $data = $request->validated();

        $slot = InterviewSlot::with('interview.application')->findOrFail($data['interview_slot_id']);

        $reschedule = InterviewReschedule::create([

            'interview_slot_id' => $slot->id,

            'requested_by' => $slot->interview->application->candidate_id,

            'date' => $data['date'],

            'old_start_time' => $slot->start_time,

            'old_end_time' => $slot->end_time,

            'new_start_time' => $data['new_start_time'],

            'new_end_time' => $data['new_end_time'],

            'reason' => $data['reason'],
        ]);

        return apiResponse(201, 'Reschedule request created successfully.', $reschedule);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $reschedule = InterviewReschedule::findOrFail($id);

        return apiResponse(
            200,
            "Reschedule retrieved successfully",
            $reschedule
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InterviewReschedule $interviewReschedules)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InterviewReschedule $interviewReschedule)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $interviewReschedule->update($data);

        return apiResponse(200, 'Updated successfully.', $interviewReschedule);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InterviewReschedule $interviewReschedules)
    {
        $interviewReschedules->delete();

        return apiResponse(200, 'Deleted successfully.');
    }
}
