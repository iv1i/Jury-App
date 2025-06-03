<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class DatabaseDumpImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dump:db-import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Загружает дамп базы даных из database/schema';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        /*try {
            $dump = Storage::get('sql_dump/dump.sql');
            if ($dump){
                DB::unprepared($dump);
                $this->info('Дамп базы данных успешно загружен!');
            }
            else{
                $this->error('Не удалось найти дамп базы данных в папке storage/app/sql_dump.');
                return;
            }
        }
        catch (\Exception $e) {
            // Обработка ошибки
            $this->error('Ошибка при загрузке дампа базы данных: '. $e->getMessage());
        }*/

        try {
            // Путь к дампу базы данных
            $dumpPath = database_path('schema/dump.sql');

            // Проверяем, существует ли файл дампа
            if (file_exists($dumpPath)) {
                $dump = file_get_contents($dumpPath);

                // Загружаем дамп в базу данных
                DB::unprepared($dump);

                $this->info('Дамп базы данных успешно загружен!');
            } else {
                $this->error('Не удалось найти дамп базы данных в папке database/schema.');
                return;
            }
        } catch (\Exception $e) {
            // Обработка ошибки
            $this->error('Ошибка при загрузке дампа базы данных: ' . $e->getMessage());
        }

    }
}
