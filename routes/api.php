<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\password\ForgetPasswordController;
use App\Http\Controllers\Auth\password\ResetPasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerifayEmailController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PositionController;
use Illuminate\Support\Facades\Route;




Route::post('/register', [RegisterController::class, 'register'])->middleware('throttle:register');

Route::controller(LoginController::class)->group(function () {
    Route::post('/login', 'login')->middleware('throttle:login');
    Route::delete('/logout', 'logout')->middleware('auth:api');
});

Route::middleware('auth:api')->prefix('email/verifay')->controller(VerifayEmailController::class)->group(function () {
    Route::post('/', 'verifay');
    Route::get('/sendotp', 'sendOtAgain')->middleware('throttle:otp');
});

Route::controller(ForgetPasswordController::class)->group(function () {
    Route::post('/forget-Password', 'forgetPassword')
        ->middleware('throttle:forgot-password');

    Route::post('/check-Otp', 'checkOtp')
        ->middleware('throttle:otp');
});

Route::post('/reset-password', [ResetPasswordController::class, 'reset'])
    ->middleware('throttle:reset-password');



Route::post('/register-candidate', [RegisterController::class, 'registerCandidate'])->middleware('throttle:register');
Route::post('/login-candidate', [LoginController::class, 'loginCandidate'])->middleware('throttle:login');


Route::middleware('auth:api')->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('positions', PositionController::class);
    Route::resource('applications', ApplicationController::class);
    Route::get('candidates' , [CandidateController::class, 'index']);
    
    Route::post('applications/{application}/accept', [ApplicationController::class, 'accept']);
    Route::post('applications/{application}/reject', [ApplicationController::class, 'reject']);
    Route::post('applications/{application}/shortlist', [ApplicationController::class, 'shortlist']);
    
    Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth:api');
});