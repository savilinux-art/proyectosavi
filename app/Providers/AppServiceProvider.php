<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use App\Models\Instalacion;
use App\Observers\InstalacionObserver;

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
        // Observer de Instalaciones (recordatorio automático al cambiar estatus)
        Instalacion::observe(InstalacionObserver::class);
        // ✅ Definir el rate limiter para las rutas API
        RateLimiter::for('api', function ($job) {
            return Limit::perMinute(60)->by($job->user()?->id ?: $job->ip());
        });

        // (Opcional) Definir un rate limiter para el webhook de Telegram sin límite
        RateLimiter::for('telegram-webhook', function ($job) {
            return Limit::none(); // Sin límite
        });
    }
}