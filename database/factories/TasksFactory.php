<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tasks>
 */
class TasksFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => 1,
            'name' => 'Test',
            'category' => 'web',
            'complexity' => 'easy',
            'description' => 'Test description',
            'decide' => 0,
            'solved' => 0,
            'price' => 1000,
            'oldprice' => 1000,
            'flag' => 'flag{flag}',
        ];
    }
}
