<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Garante que existe um Admin principal (caso ainda não exista)
        User::firstOrCreate(
            ['email' => 'admin@mymishi.pt'],
            [
                'name'     => 'admin',
                'password' => Hash::make('password'),
                'role'     => 'admin',
                'photo'    => 'images/avatars/01.png',
            ]
        );

        // 2. Garante que existe um Moderador de teste
        User::firstOrCreate(
            ['email' => 'mod@mymishi.pt'],
            [
                'name'     => 'mod',
                'password' => Hash::make('password'),
                'role'     => 'mod',
                'photo'    => 'images/avatars/02.png',
            ]
        );

        // 3. Cria 40 utilizadores comuns ('user') com avatares aleatórios
        User::factory(40)->create();
    }
}
