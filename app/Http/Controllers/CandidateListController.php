<?php

namespace App\Http\Controllers;

use App\Http\Resources\Candidate\CandidateListResource;
use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CandidateListController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $company = Auth::guard('api')->user();

        $candidates = Candidate::with('applications.position')
            ->whereHas('applications.position', function ($query) use ($company) {
                $query->where('company_id', $company->id);
            })
            ->get();

        return apiResponse(200, 'Candidates retrieved successfully', CandidateListResource::collection($candidates));
    }
}
