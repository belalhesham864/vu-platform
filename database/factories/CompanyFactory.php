<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
      $name = fake()->unique()->company();

        return [
            'company_name' => $name,
            'slug' => Str::slug($name),
            'industry' => fake()->randomElement([
                'Software',
                'Healthcare',
                'Finance',
                'Education',
                'Marketing'
            ]),
            'location' => fake()->city(),
            'about' => fake()->paragraph(),
            'phone' => fake()->unique()->numerify('010########'),
            'logo' => 'companies/default.png',
            'website' => fake()->url(),
            'company_size' => fake()->randomElement([
                '1-10',
                '11-50',
                '51-200',
                '201-500',
                '500+'
            ]),
            'status' => fake()->randomElement([
                'pending',
                'active',
                'suspended'
            ]),
        ];
    }
}
