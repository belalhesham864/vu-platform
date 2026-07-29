<?php

namespace App\Http\Controllers;

use App\Http\Requests\Application\ApplicationRequest;
use App\Http\Resources\Application\ApplicationResource;
use App\Models\Application;
use App\Models\Candidate;
use App\Services\Applications\ApplicationService;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!auth()->user()->can('view_application')) {
            return apiResponse(403 , 'You Can Not View Applications');
        }

        $applications = QueryBuilder::for(Application::class)
            ->allowedFilters(
                'status',
                'decision',
                AllowedFilter::callback('name', function ($query, $value) {
                    $query->whereHas('candidate', function ($q) use ($value) {
                        $q->where('name', 'like', "%{$value}%");
                    });
                }),
            )
            ->allowedSorts('created_at', 'decision_date')
            ->paginate();

        return apiResponse(200, 'Applications retrieved successfully', ApplicationResource::collection($applications));
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
    public function store(ApplicationRequest $request, ApplicationService $applicationService)
    {
        if (!auth()->user()->can('create_application')) {
            return apiResponse(403 , 'You Can Not Create Applications');
        }

        try {
            $company = Auth::guard('api')->user();

            $application = $applicationService->create($request->validated(), $request, $company);

            return apiResponse(
                201,
                'Application created successfully',
                new ApplicationResource($application)
            );
        } catch (\Exception $e) {
            return apiResponse(500, $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Application $application)
    {
        return apiResponse(200, 'Application retrieved successfully', new ApplicationResource($application));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Application $application)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ApplicationRequest $request, Application $application)
    {
        if (!auth()->user()->can('edit_application')) {
            return apiResponse(403 , 'You Can Not Edit Applications');
        }

        $company = Auth::guard('api')->user();
        $validatedData = $request->validated();
        $candidateId = Candidate::findOrFail($validatedData['candidate_id']);
        $validatedData['candidate_id'] = $candidateId->id;
        $validatedData['approved_by'] = $company->id;

        $application->update($validatedData);

        return apiResponse(200, 'Application updated successfully', new ApplicationResource($application));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Application $application)
    {
        if (!auth()->user()->can('delete_application')) {
            return apiResponse(403 , 'You Can Not Delete Applications');
        }

        $application->delete();

        return apiResponse(200, 'Application deleted successfully', null);
    }

    public function decision(Application $application, string $decision)
    {
        $decisions = [
            'accept' => 'Accepted',
            'reject' => 'Rejected',
            'shortlist' => 'Shortlisted',
        ];

        if (! array_key_exists($decision, $decisions)) {
            return apiResponse(422, 'Invalid decision');
        }

        $application->update([
            'status' => $decisions[$decision],
            'decision' => $decisions[$decision],
            'decision_date' => now(),
        ]);

        return apiResponse(
            200,
            "Application {$decisions[$decision]} successfully",
            new ApplicationResource($application)
        );
    }
}
