<?php

namespace Database\Seeders;

use App\Models\Interview;
use App\Models\InterviewSlot;
use Illuminate\Database\Seeder;

class InterviewSlotSeeder extends Seeder
{
    public function run(): void
    {
        $invitations = Interview::all();

        foreach ($invitations as $invitation) {

            InterviewSlot::create([
                'invitation_id' => $invitation->id,
                'date' => now()->addDays(1)->toDateString(),
                'start_time' => '10:00:00',
                'end_time' => '10:30:00',
                'is_booked' => false,
            ]);

            InterviewSlot::create([
                'invitation_id' => $invitation->id,
                'date' => now()->addDays(2)->toDateString(),
                'start_time' => '12:00:00',
                'end_time' => '12:30:00',
                'is_booked' => false,
            ]);

            InterviewSlot::create([
                'invitation_id' => $invitation->id,
                'date' => now()->addDays(3)->toDateString(),
                'start_time' => '14:00:00',
                'end_time' => '14:30:00',
                'is_booked' => false,
            ]);
        }
    }
}
