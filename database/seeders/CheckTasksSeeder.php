<?php

namespace Database\Seeders;

use App\Models\CheckTasks;
use Illuminate\Database\Seeder;

class CheckTasksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CheckTasks::factory()->create([
            'id' => 1,
            'user_id' => 1,
            'sumary' => 0,
            'easy' => 0,
            'medium' => 0,
            'hard' => 0,
        ]);
    }
}
