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
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InterviewController;
use App\Http\Controllers\InterviewRescheduleController;
use App\Http\Controllers\InterviewSlotController;
use App\Http\Controllers\MangmentTeamController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\Dashboard\AdminDashboardController;
use App\Http\Controllers\Dashboard\HrDashboardController;
use App\Http\Controllers\Dashboard\HrInterviewerDashboardController;
use App\Http\Controllers\Dashboard\TechInterviewerDashboardController;
use App\Http\Controllers\Dashboard\AccountManagerDashboardController;
use App\Http\Controllers\Dashboard\OwnerDashboardController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\PositionStageController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TeamMemberController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('Home/state', [HomeController::class, 'state']);
Route::post('Home/subscribers', [HomeController::class, 'subscriber']);

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

    Route::resource('interviews', InterviewController::class);
    Route::get('interview-slots/{interviewId}', [InterviewSlotController::class , 'index']);
    Route::post('interview-slots', [InterviewSlotController::class , 'store']);
    Route::put('interview-slots/{interviewSlot}', [InterviewSlotController::class , 'update']);
    Route::get('interview-slots/{interviewSlot}', [InterviewSlotController::class , 'show']);
    Route::delete('interview-slots/{interviewSlot}', [InterviewSlotController::class , 'destroy']);
    
    Route::get('allReschedules/{slotId}', [InterviewRescheduleController::class , 'index']);
    Route::post('interview-reschedules', [InterviewRescheduleController::class , 'store']);
    Route::put('interview-reschedules/{interviewReschedule}', [InterviewRescheduleController::class , 'update']);
    Route::get('interview-reschedules/{id}', [InterviewRescheduleController::class , 'show']);
    Route::delete('interview-reschedules/{interviewReschedule}', [InterviewRescheduleController::class , 'destroy']);

    Route::resource('evaluations', EvaluationController::class);
    Route::resource('position-stages', PositionStageController::class);
    Route::get('team-members', [TeamMemberController::class, 'index']);

    Route::prefix('dashboard')->group(function () {
        Route::get('/owner', [OwnerDashboardController::class,'index']);
        Route::get('/admin',[AdminDashboardController::class, 'index']);
      
    });

    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationsController::class, 'allNotifications']);
        Route::get('/unread', [NotificationsController::class, 'unReadNotifications']);
        Route::put('/{notification}/read', [NotificationsController::class, 'markAsRead']);
        Route::put('/read-all', [NotificationsController::class, 'markAllAsRead']);
        Route::delete('/{notification}', [NotificationsController::class, 'destroy']);
    });

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

Route::get('/plans', [PlanController::class, 'index']);
Route::get('/plans/{plan}', [PlanController::class, 'show']);

Route::post('payments/create', [PaymentController::class, 'create'])->middleware('auth:api');
Route::post('/payments/subscription', [PaymentController::class, 'subscription'])->middleware('auth:api');
Route::post('/stripe/webhook', [PaymentController::class, 'webhook']);
