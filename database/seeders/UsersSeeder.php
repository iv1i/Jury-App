<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'id' => 1,
            'name' => 'Team A',
            'password' => Hash::make('1111'),
            'teamlogo' => 'StandartLogo.png',
            'GuestLogo' => '',
            'players' => 6,
            'wherefrom' => 'Россия',
            'guest' => 'Yes',
            'scores' => 0,
        ]);
    }
}
