<?php

namespace Database\Seeders;

use App\Models\Interview;
use App\Models\Application;
use App\Models\User;
use Illuminate\Database\Seeder;

class InterviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $applications = Application::pluck('id')->toArray();
        $interviewers = User::role(['Tech-Interviewer', 'HR-Interviewer'])->pluck('id')->toArray();

        if (empty($applications) || empty($interviewers)) {
            return;
        }

        foreach (range(1, 20) as $i) {
            Interview::create([
                'application_id'      => fake()->randomElement($applications),
                'interviewer_id'      => fake()->randomElement($interviewers),
                'available_until'     => fake()->dateTimeBetween('now', '+7 days'),
                'estimated_duration'  => fake()->randomElement([30, 45, 60]),
                'question_count'      => fake()->numberBetween(5, 20),
                'status'              => fake()->randomElement([
                    'pending',
                    'accepted',
                    'expired',
                    'completed',
                ]),
            ]);
        }
    }
}
