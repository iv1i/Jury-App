<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\infoTasks>
 */
class infoTasksFactory extends Factory
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
            'sumary' => 0,
            'easy' => 0,
            'medium' => 0,
            'hard' => 0,
            'admin' => 0,
            'recon' => 0,
            'crypto' => 0,
            'stegano' => 0,
            'ppc' => 0,
            'pwn' => 0,
            'web' => 0,
            'forensic' => 0,
            'joy' => 0,
            'misc' => 0,
            'osint' => 0,
            'reverse' => 0,
        ];
    }
}
