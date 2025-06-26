<?php

namespace App\Providers;

use App\Services\SettingsService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
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

        // Проверка и создание папки для логотипов + копирование стандартного лого
        $this->ensureTeamLogoDirectoryExists();
    }

    protected function ensureTeamLogoDirectoryExists(): void
    {
        $logoDirectory = storage_path('app/public/teamlogo');
        $defaultLogoPath = public_path('media/img/StandartLogo.png');
        $destinationLogoPath = $logoDirectory . '/StandartLogo.png';

        // Создаем папку, если ее нет
        if (!File::exists($logoDirectory)) {
            File::makeDirectory($logoDirectory, 0755, true);
        }

        // Копируем стандартное лого, если его нет в целевой папке
        if (File::exists($defaultLogoPath) && !File::exists($destinationLogoPath)) {
            File::copy($defaultLogoPath, $destinationLogoPath);
        }
    }
}
