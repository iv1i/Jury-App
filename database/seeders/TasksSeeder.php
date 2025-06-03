<?php

namespace Database\Seeders;

use App\Models\Tasks;
use Illuminate\Database\Seeder;

class TasksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tasks::factory()->create([
            'id' => 1,
            'name' => 'Test',
            'category' => 'web',
            'complexity' => 'easy',
            'description' => 'Test description',
            'decide' => 0,
            'solved' => 0,
            'FILES' => null,
            'price' => 1000,
            'oldprice' => 1000,
            'flag' => 'flag{flag}',
        ]);
    }
}
