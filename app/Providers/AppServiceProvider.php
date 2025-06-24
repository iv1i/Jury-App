<?php

namespace App\Providers;

use App\Services\SettingsService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(SettingsService::class, function ($app) {
            return new SettingsService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        /*RateLimiter::for('AuthApp', function (Request $request) {
            return Limit::perMinute(3);
        });*/
        if (!is_link(public_path('storage'))){
            Artisan::call('storage:link');
        }
        if (env('REVERB_HOST') === 'auto') {
            $ip = gethostbyname(gethostname());
            config(['reverb.host' => $ip]);
        }
    }
}
