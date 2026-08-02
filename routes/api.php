<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\password\ForgetPasswordController;
use App\Http\Controllers\Auth\password\ResetPasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerifayEmailController;
use App\Http\Controllers\CandidateListController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\InterviewController;
use App\Http\Controllers\InterviewReschedulesController;
use App\Http\Controllers\InterviewSlotsController;
use App\Http\Controllers\MangmentTeamController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\PositionStageController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TeamMemberController;
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


Route::post('/register-candidate', [RegisterController::class, 'registerCandidate'])->middleware('throttle:register');
Route::post('/login-candidate', [LoginController::class, 'loginCandidate'])->middleware('throttle:login');


Route::middleware('auth:api')->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('positions', PositionController::class);
    Route::resource('applications', ApplicationController::class);
    Route::get('candidates', CandidateListController::class);
    Route::post('applications/{application}/{decision}', [ApplicationController::class, 'decision']);

    Route::get('interviews/{interview}/slots', [InterviewSlotsController::class, 'index']);
    Route::post('interview-slots', [InterviewSlotsController::class, 'store']);
    Route::put('interview-slots/{interviewSlot}', [InterviewSlotsController::class, 'update']);
    Route::get('interview-slots/{interviewSlot}', [InterviewSlotsController::class, 'show']);
    Route::get('interview-slots/{interviewSlot}', [InterviewSlotsController::class, 'destroy']);

    Route::resource('interviews', InterviewController::class);

    Route::get('interviews/{slotId}/reschedules', [InterviewReschedulesController::class, 'index']);
    Route::post('interview-reschedules', [InterviewSlotsController::class, 'store']);
    Route::put('interview-reschedules/{interviewReschedule}', [InterviewReschedulesController::class, 'update']);
    Route::get('interview-reschedules/{id}', [InterviewReschedulesController::class, 'show']);
    Route::delete('interview-reschedules/{interviewReschedule}', [InterviewReschedulesController::class, 'destroy']);

    Route::resource('evaluations', EvaluationController::class);
    Route::resource('position-stages', PositionStageController::class);
    Route::get('team-members', [TeamMemberController::class, 'index']);

    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationsController::class, 'allNotifications']);
        Route::get('/unread', [NotificationsController::class, 'unReadNotifications']);
        Route::put('/{id}/read', [NotificationsController::class, 'markAsRead']);
        Route::put('/read-all', [NotificationsController::class, 'markAllAsRead']);
    });

    Route::post('/logout', [LoginController::class, 'logout']);
});

Route::controller(SettingController::class)->middleware('auth:api')->prefix('setting/')->group(function () {
    Route::get('/', 'show');
    Route::put('/update', 'update');
});
Route::controller(MangmentTeamController::class)->middleware('auth:api')->prefix('team/')->group(function () {
    Route::get('/', 'index');
    Route::Post('/invite', 'invite');
    Route::put('/update', 'update')->name('update');
    Route::patch('/{user}', [MangmentTeamController::class, 'update']);
    Route::post('/{id}/resend-invite', [MangmentTeamController::class, 'resendInvite']);
    Route::delete('/{id}', [MangmentTeamController::class, 'delete']);
});
Route::post('/set-password', [MangmentTeamController::class, 'resetPassword']);
