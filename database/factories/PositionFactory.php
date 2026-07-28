<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Company;
use App\Models\Position;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Position>
 */
class PositionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
           return [
            'company_id' => Company::inRandomOrder()->first()->id,
            'category_id' => Category::inRandomOrder()->first()?->id,
            'title' => fake()->jobTitle(),
            'description' => fake()->paragraph(3),
            'requirements' => fake()->paragraph(2),
            'work_location' => fake()->randomElement([
                'On-site',
                'Remote',
                'Hybrid'
            ]),
            'salary' => fake()->numberBetween(5000, 30000),
            'employment_type' => fake()->randomElement([
                'Full-time',
                'Part-time',
                'Contract',
                'Internship'
            ]),
            'status' => fake()->randomElement([
                'Open',
                'Closed'
            ]),
            'application_deadline' => fake()->dateTimeBetween('now', '+2 months'),
            'start_date' => fake()->dateTimeBetween('+3 days', '+3 months'),
            'end_date' => fake()->dateTimeBetween('+4 months', '+8 months'),
            'approved_by' => User::inRandomOrder()->first()?->id,
        ];
    }
}
