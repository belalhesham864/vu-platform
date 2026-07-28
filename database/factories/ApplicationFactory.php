<?php

namespace Database\Factories;

use App\Models\Application;
use App\Models\Candidate;
use App\Models\Position;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Application>
 */
class ApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
         return [
            'candidate_id' => Candidate::inRandomOrder()->first()->id,
            'position_id' => Position::inRandomOrder()->first()->id,

            'application_type' => fake()->randomElement([
                'AI Interview',
                'Technical Interview',
                'Final Interview',
            ]),

            'status' => fake()->randomElement([
                'Under Review',
                'Scheduled',
                'Shortlisted',
                'Accepted',
                'Rejected',
            ]),

            'decision' => fake()->randomElement([
                'Approved',
                'Pending',
                'Rejected',
                null
            ]),

            'decision_date' => fake()->optional()->date(),

            'start_date' => fake()->optional()->date(),

            'approved_by' => User::inRandomOrder()->first()?->id,
        ];
    }
}
