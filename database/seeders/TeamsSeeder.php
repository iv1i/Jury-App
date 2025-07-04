<?php

namespace Database\Seeders;

use App\Models\Teams;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TeamsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Teams::factory()->create([
            'id' => 1,
            'token' => Str::random(32),
            'name' => 'Team A',
            'password' => Hash::make('1111'),
            'teamlogo' => 'StandartLogo.png',
            'players' => 6,
            'wherefrom' => 'Россия',
            'guest' => 'Yes',
            'scores' => 0,
        ]);
    }
}
