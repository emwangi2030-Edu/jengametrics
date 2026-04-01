<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        // Ensure all generated URLs use HTTPS when behind a TLS-terminating proxy (staging/production).
        if ($this->app->environment('production', 'staging')) {
            URL::forceScheme('https');
        }
    }

    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api-auth', function (Request $request) {
            return Limit::perMinute(10)->by($request->ip());
        });

        RateLimiter::for('api-register', function (Request $request) {
            return Limit::perMinute(3)->by($request->ip());
        });
    }
}
