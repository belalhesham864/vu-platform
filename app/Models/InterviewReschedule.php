<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InterviewReschedule extends Model
{
    protected $fillable = [
        'interview_slot_id',
        'requested_by',
        'date',
        'old_start_time',
        'old_end_time',
        'new_start_time',
        'new_end_time',
        'reason',
        'status',
    ];

    public function interview()
    {
        return $this->belongsTo(Interview::class);
    }

    public function slot()
    {
        return $this->belongsTo(InterviewSlot::class);
    }
    public function requster()
    {
        return $this->belongsTo(Candidate::class , 'requested_by');
    }
}
