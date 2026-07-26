<?php

namespace App\services\Auth;

use App\Http\Resources\AuthCandidate\AuthResource;
use App\Models\Candidate;
use App\Utils\ImageManger;
use Illuminate\Support\Facades\Auth;

class CandidateService
{
    public function registerCandidate($request)
    {
        $data = $request->validated();

        $data['password'] = bcrypt($data['password']);

        if ($request->hasFile('cv_file')) {
            $data['cv_file'] = ImageManger::uploadImage($request, 'cv_file');
        }
        
        $candidate = Candidate::create($data);

        $token = Auth::guard('candidate')->login($candidate);

        if (!$token) {
            return apiResponse(401, "Unauthorized");
        }

        return [
            'user' => new AuthResource($candidate),
            'token' => $token,
        ];
    }

    public function loginCandidate($request)
    {
        $credentials = $request->validated();

        if (!$token = Auth::guard('candidate')->attempt($credentials)) {
            return apiResponse(401, "Unauthorized");
        }

        $candidate = Auth::guard('candidate')->user();

        return [
            'user' => new AuthResource($candidate),
            'token' => $token,
        ];
    }
}
