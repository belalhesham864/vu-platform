<?php

namespace App\Http\Controllers\Auth\password;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
       public function reset(ResetPasswordRequest $request)
    {
       $data= $request->validated();
        $user = User::where('email', $data['email'])->first();
            $user->update(['password' => Hash::make($data['password'])]);
            return apiResponse(200, 'Password Changed Successfully');
     
    }
}
