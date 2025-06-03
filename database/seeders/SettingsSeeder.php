<?php

namespace Database\Seeders;

use App\Models\Settings;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Settings::factory()->create([
            'id' => 1,
            'Rules' => 'yes',
            'Projector' => 'yes',
            'Home' => 'yes',
            'Scoreboard' => 'yes',
            'Statistics' => 'yes',
            'Logout' => 'yes',
            'Rule' => 'Правила редактируются в Админке!'
        ]);
    }
}
