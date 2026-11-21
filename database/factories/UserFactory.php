<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        // Gera um número aleatório entre 01 e 17 para os teus avatares predefinidos
        $avatarNumber = str_pad(fake()->numberBetween(1, 17), 2, '0', STR_PAD_LEFT);

        return [
            'name'              => fake()->unique()->firstName() . ' ' . fake()->lastName(),
            'email'             => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password'          => static::$password ??= Hash::make('password'),
            'role'              => 'user',
            'address'           => fake()->streetAddress() . ', ' . fake()->city(),
            'nif'               => fake()->numerify('2########'), // NIF português realista (9 dígitos)
            'photo'             => "images/avatars/{$avatarNumber}.png",
            'remember_token'    => Str::random(10),
        ];
    }
}
