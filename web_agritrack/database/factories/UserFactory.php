<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
{
    return [
        'nama' => fake()->name(),
        'jabatan' => fake()->randomElement(['Manager', 'Staff', 'Admin', 'Supervisor']),
        'alamat' => fake()->address(),
        'no_hp' => fake()->numberBetween(81100000000, 81999999999), // Menghasilkan nomor hp berupa string angka
        'role' => fake()->randomElement(['admin', 'owner', 'karyawan']),
        'gaji' => fake()->numberBetween(3000000, 10000000),
        'username' => fake()->unique()->userName(),
        'password' => \Illuminate\Support\Facades\Hash::make('password'), 
    ];
}

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
