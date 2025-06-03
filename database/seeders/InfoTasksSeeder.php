<?php

namespace Database\Seeders;

use App\Models\infoTasks;
use Illuminate\Database\Seeder;

class InfoTasksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        infoTasks::factory()->create([
            'id' => 1,
            'sumary' => 1,
            'easy' => 1,
            'medium' => 0,
            'hard' => 0,
            'admin' => 0,
            'recon' => 0,
            'crypto' => 0,
            'stegano' => 0,
            'ppc' => 0,
            'pwn' => 0,
            'web' => 1,
            'forensic' => 0,
            'joy' => 0,
            'misc' => 0,
            'osint' => 0,
            'reverse' => 0,
        ]);
    }
}
