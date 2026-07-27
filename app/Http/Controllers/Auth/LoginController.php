<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\User\LoginCandidateRequest;
use App\Http\Resources\AuthCandidate\AuthResource;
use App\Http\Resources\UserResource;
use App\services\Auth\CandidateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
  public function login(LoginRequest $request)
  {
    $data = $request->validated();
    $token = Auth::guard('api')->attempt($data);
    if (!$token) {
      return apiResponse(401, 'Unauthorized');
    }

    $user = auth('api')->user();

    return apiResponse(200, 'Login Success', ['user' => new UserResource($user), 'token' => $token]);
  }
  public function logout()
  {
    auth('api')->logout();
    return apiResponse(200, 'Logout Successfuly');
  }


  public function loginCandidate(LoginCandidateRequest $request, CandidateService $candidateService)
  {
    try {
      $data = $candidateService->loginCandidate($request);

      return apiResponse(200, 'Login successful', [
        'user' => $data['user'],
        'authorization' => [
          'token' => $data['token'],
          'type' => 'Bearer',
        ],
      ]);
    } catch (\Exception $e) {

      return apiResponse(
        $e->getCode() ?: 500,
        $e->getMessage()
      );
    }
  }
}
