<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\verfaidEmailOtpNotification;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;

class VerifayEmailController extends Controller
{
     public $otp;
    public function __construct()
    {
        $this->otp = new Otp();
    }
    public function verifay(Request $request)
    {
        $data = $request->validate(['token' => 'required']);
        try {
            $user = auth('api')->user();
            $check = $this->otp->validate($user->email, $data['token']);

            if (!$check->status) {
                throw new \Exception('Otp is Invalid', 400);
            }

            $user->update(['email_verified_at' => now()]);

            return apiResponse(200, 'Email Verifaied Success');
        } catch (\Exception $e) {
            $code=$e->getCode();
         $code=$code==0 ? 500 : $code;
            return apiResponse($code, $e->getMessage());
        }
    }
    public function sendOtAgain()
    {
        $user = auth()->user();
        $user->notify(new verfaidEmailOtpNotification());
        return apiResponse(200, 'Otp send Successfuly');
    }
}
