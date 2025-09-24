<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;

class RouteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->configureRateLimiting();

        // API routes: prefix with /api and apply api middleware group
        Route::prefix('api')
            ->middleware('api')
            ->group(base_path('routes/api.php'));

        // Web routes
        Route::middleware('web')
            ->group(base_path('routes/web.php'));
    }

    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });

        // fallback if code references model-based limiter, define a permissive limiter
        RateLimiter::for('\App\\Models\\User::api', function (Request $request) {
            return Limit::perMinute(1000)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
