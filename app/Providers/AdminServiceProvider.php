<?php

namespace App\Providers;

use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\QueryException;
use Illuminate\Database\ConnectionException;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

class AdminServiceProvider extends ServiceProvider
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
        try {
            // Проверяем соединение с БД перед выполнением запросов
            if (!\DB::connection()->getPdo()) {
                Log::warning('Нет подключения к базе данных. Пропуск создания администратора.');
                return;
            }

            // Проверка существования таблицы admins только если соединение установлено
            if (Schema::hasTable('admins')) {
                $admin = config('admin');

                // Проверяем, есть ли конфигурация администратора
                if (empty($admin) || empty($admin['password'])) {
                    Log::warning('Конфигурация администратора не найдена или пароль не установлен.');
                    return;
                }

                Admin::updateOrCreate(
                    ['name' => 'admin'],
                    ['password' => Hash::make($admin['password'])]
                );
            }
        }  catch (Exception $e) {
            Log::warning('Ошибка при работе с базой данных: ' . $e->getMessage());
        }
    }
}
