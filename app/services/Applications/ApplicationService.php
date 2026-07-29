<?php

namespace App\Services\Applications;

use App\Models\Application;
use App\Models\Candidate;
use App\Utils\ImageManger;
use Illuminate\Support\Facades\DB;

class ApplicationService
{
    public function create(array $validatedData, $request, $company)
    {
        return DB::transaction(function () use ($validatedData, $request, $company) {

            if ($request->hasFile('cv_file')) {
                $validatedData['cv_file'] = ImageManger::uploadImage($request, 'cv_file');
            }

            $candidate = Candidate::firstOrCreate(
                [
                    'email' => $validatedData['email'],
                ],
                [
                    'name' => $validatedData['name'],
                    'phone' => $validatedData['phone'],
                    'cv_file' => $validatedData['cv_file'] ?? null,
                ]
            );

            return Application::create([
                'candidate_id' => $candidate->id,
                'position_id' => $validatedData['position_id'],
                'approved_by' => $company->id,
            ]);
        });
    }
}