<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DatabaseDumpExport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dump:db-export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Создает дамп базы даных в database/schema';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $path = database_path('schema/dump.sql');

            $database = env('DB_DATABASE');
            $username = env('DB_USERNAME');
            $password = env('DB_PASSWORD');
            $host = env('DB_HOST', '127.0.0.1');

            $command = "mysqldump --user={$username} --password={$password} --host={$host} --no-tablespaces {$database} > {$path} 2>/dev/null";

            exec($command, $output, $returnVar);

            if ($returnVar === 0) {
                $this->info('Дамп базы данных успешно создан: ' . $path);
            } else {
                $this->error('Ошибка при создании дампа базы данных');
            }
        } catch (\Exception $e) {
            $this->error('Ошибка при создании дампа базы данных: '. $e->getMessage());
        }
    }
}
