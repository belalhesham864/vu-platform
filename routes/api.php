<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\password\ForgetPasswordController;
use App\Http\Controllers\Auth\password\ResetPasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerifayEmailController;
use Illuminate\Http\Request;
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