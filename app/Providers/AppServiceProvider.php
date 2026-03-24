<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        // Ensure all generated URLs use HTTPS when behind a TLS-terminating proxy (staging/production).
        if ($this->app->environment('production', 'staging')) {
            URL::forceScheme('https');
        }
    }
}
