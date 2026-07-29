<?php

namespace App\Http\Controllers;

use App\Http\Resources\Candidate\CandidateListResource;
use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class CandidateListController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        if (!auth()->user()->can('view_candidates')) {
            return apiResponse(403 , 'You Can Not View Any Candidate');
        }
        $company = Auth::guard('api')->user()->company;

        $candidates = QueryBuilder::for(Candidate::class)
            ->with('applications.position')
            ->whereHas('applications.position', function ($query) use ($company) {
                $query->where('company_id', $company->id);
            })
            ->allowedFilters(
                AllowedFilter::partial('name'),
                AllowedFilter::exact('applications.status'),
                AllowedFilter::partial('applications.position.title')
            )
            ->paginate();

        return apiResponse(200, 'Candidates retrieved successfully', CandidateListResource::collection($candidates));
    }
}
