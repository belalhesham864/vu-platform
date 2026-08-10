<?php

namespace Database\Seeders;

use App\Models\Interview;
use App\Models\InterviewReschedule;
use App\Models\InterviewSlot;
use Illuminate\Database\Seeder;

class InterviewRescheduleSeeder extends Seeder
{
    public function run(): void
    {
        $invitations = Interview::all();

        foreach ($invitations as $invitation) {

            $slot = InterviewSlot::where('invitation_id', $invitation->id)
                ->first();

            if (!$slot) {
                continue;
            }

            InterviewReschedule::create([
                'invitation_id' => $invitation->id,
                'slot_id' => $slot->id,
                'status' => 'pending',
            ]);
        }
    }
}
