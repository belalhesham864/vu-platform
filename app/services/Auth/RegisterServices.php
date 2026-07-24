<?php

namespace App\services\Auth;

use App\Models\Company;
use App\Models\User;
use App\Notifications\verfaidEmailOtpNotification;
use App\Utils\ImageManger;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterServices
{
    public function valdiation($request)
    {
        $validtor = Validator::make($request->all(), $request->rules());
        if ($validtor->fails()) {
            throw new Exception($validtor->errors());
        }
        return $validtor->validated();
    }
    public function store($request, $data)
    {
        try {
            DB::beginTransaction();
            if ($request->hasFile('logo')) {
                $data['logo'] = ImageManger::uploadImage($request, 'logo');
            }
            $company =  Company::create([
                'company_name' => $data['company_name'],
                'industry' => $data['industry'],
                'location' => $data['location'],
                'about' => $data['about'],
                'phone' => $data['phone'],
                'website' => $data['website'],
                'company_size' => $data['company_size'],
                'logo' => $data['logo'],
            ]);
            $user = User::create([
                'name'       => $data['name'],
                'email'      => $data['email'],
                'password'   => Hash::make($data['password']),
                'company_id' => $company->id,
            ]);
            $user->assignRole('Owner');

            DB::commit();
return $user;
            } catch (\Exception $e) {
            DB::rollBack();
                        $code=$e->getCode();
         $code=$code==0 ? 500 : $code;
            return apiResponse($code, ['message' => $e->getMessage()]);
        }
    }
    public function sendOtp($user)
    {
        $user->notify(new verfaidEmailOtpNotification());
    }
    public function createToken($user)
    {
        $token = auth()->login($user);

        return $token;
    }
    public  function Register($request)
    {
        try {

            $data = $this->valdiation($request);

            $user = $this->store($request, $data);
            $this->sendOtp($user);
            $token =  $this->createToken($user);
            return apiResponse(201, ['message' => 'Account created successfully, a verification email has been sent to you.', 'token' => $token],);
        } catch (\Exception $e) {
            return apiResponse(422, $e->getMessage());
        }
    }
}
