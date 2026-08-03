<?php

namespace Database\Factories;

<<<<<<< HEAD
=======
use App\Models\Company;
>>>>>>> f699f1ba736d0b6a808622a59cbea248d4b5b091
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
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
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
<<<<<<< HEAD
=======
                  'status' => fake()->randomElement([
                'active',
                'inactive',
                'invited'
            ]),
                        'company_id' => Company::inRandomOrder()->value('id'),

>>>>>>> f699f1ba736d0b6a808622a59cbea248d4b5b091
            'remember_token' => Str::random(10),
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
