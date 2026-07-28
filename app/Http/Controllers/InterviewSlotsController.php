<?php

namespace App\Http\Controllers;

use App\Http\Requests\Slot\SlotRequest;
use App\Http\Resources\Slot\SlotResource;
use App\Models\Interview;
use App\Models\InterviewSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InterviewSlotsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($interviewId)
    {
        $slots = InterviewSlot::where('interview_id', $interviewId)->paginate();

        return apiResponse(200, "Slots retrieved successfully", SlotResource::collection($slots));
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
        $validated = $request->validated();

        $interview = Interview::where('application_id', $validated['application_id'])
            ->firstOrFail();

        $validated['interview_id'] = $interview->id;
        $slot = InterviewSlot::create($validated);


        return apiResponse(200, "created successfully", $slot);
    }

    /**
     * Display the specified resource.
     */
    public function show(InterviewSlot $interviewSlot)
    {
        $interviewSlot->load([
            'interview.application.candidate',
            'interview.interviewer'
        ]);

        return apiResponse(200, 'Success', new SlotResource($interviewSlot));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InterviewSlot $interviewSlots)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SlotRequest $request, InterviewSlot $interviewSlot)
    {
        $data = $request->validated();

        $interview = Interview::where('application_id', $data['application_id'])
            ->firstOrFail();

        $data['interview_id'] = $interview->id;

        $interviewSlot->update($data);

        return apiResponse(200, "updated successfully", $interviewSlot);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InterviewSlot $interviewSlot)
    {
        $interviewSlot->delete();

        return apiResponse(200, 'deleted successfully');
    }
}
