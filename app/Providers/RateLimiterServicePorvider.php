<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;

class RateLimiterServicePorvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
public function boot(): void
{
    foreach (['register', 'login', 'otp', 'forgot-password', 'reset-password'] as $limiter) {

        RateLimiter::for($limiter, function (Request $request) {
            return Limit::perHour(7)
                ->by($request->user()?->id ?: $request->ip())
                ->response(function () {
                    return apiResponse(429, 'Try again after 60 minutes.');
                });
        });

    }
}
}
