<?php

namespace App\Http\Controllers;

use App\Http\Requests\Interview\InterviewRequest;
use App\Http\Resources\Interview\InterviewResource;
use App\Models\Interview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class InterviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!auth()->user()->can('view_interviews')) {
            return apiResponse(403 , 'You Can Not View Interviews');
        }

        $interviews = QueryBuilder::for(Interview::class)
            ->with(['application.candidate', 'application.position', 'interviewer' , 'slots'])
            ->allowedFilters(
                AllowedFilter::callback('candidate', function ($query, $value) {
                    $query->whereHas('application.candidate', function ($q) use ($value) {
                        $q->where('name', 'like', "%{$value}%");
                    });
                }),

                AllowedFilter::callback('position', function ($query, $value) {
                    $query->whereHas('application.position', function ($q) use ($value) {
                        $q->where('title', 'like', "%{$value}%");
                    });
                }),

                AllowedFilter::callback('status', function ($query, $value) {
                    $query->whereHas('application', function ($q) use ($value) {
                        $q->where('status', $value);
                    });
                })
            )
            ->paginate();
            
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
        if (!auth()->user()->can('create_interview')) {
            return apiResponse(403 , 'You Can Not Create Interview');
        }

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
        if (!auth()->user()->can('edit_interview')) {
            return apiResponse(403 , 'You Can Not Edit Interview');
        }

        $validated = $request->validated();

        $interview->update($validated);

        return apiResponse(200, 'Interview updated successfully', new InterviewResource($interview));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Interview $interview)
    {
        if (!auth()->user()->can('delete_interview')) {
            return apiResponse(403 , 'You Can Not Delete Interview');
        }

        $interview->delete();

        return apiResponse(200, 'Interview deleted successfully', null);
    }
}
