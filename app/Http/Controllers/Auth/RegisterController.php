<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RgisterRequest;
use App\Http\Requests\User\RegisterCandidateRequest;
use App\Http\Resources\AuthCandidate\AuthResource;
use App\Models\Candidate;
use App\Models\Company;
use App\Models\User;
use App\Notifications\verfaidEmailOtpNotification;
use App\services\Auth\CandidateService;
use App\services\Auth\RegisterServices;
use App\Utils\ImageManger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
  public function register(RgisterRequest $request, RegisterServices $register)
  {
    return $register->Register($request);
  }


  public function registerCandidate(RegisterCandidateRequest $request, CandidateService $candidateService)
  {
    try {
      $data = $candidateService->registerCandidate($request);

      return apiResponse(200, 'Created', [
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
