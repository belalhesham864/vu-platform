<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\SettingResource;
use App\Utils\ImageManger;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function show()
    {
        $company = auth()->user()->company()->with('owner')
            ->first();
        // return $company;
        return apiResponse(200, 'company information', new SettingResource($company));
    }
    public function update(UpdateCompanyRequest $request)
    {
        $data = $request->validated();
        $company = auth()->user()->company;
        if ($request->hasFile('logo')) {
            ImageManger::delete($company->logo);
            $data['logo'] = ImageManger::uploadImage($request, 'logo');
        }
        $updatedata = $company->update($data);
        if (!$updatedata) {
            return apiResponse(400, 'Please Try Again Latter');
        }
        return apiResponse(200, 'Company updated successfully', new SettingResource($company->fresh('owner')));
    }
}
