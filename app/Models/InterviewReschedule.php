<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterviewReschedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'interview_slot_id',
        'date',
        'old_start_time',
        'old_end_time',
        'new_start_time',
        'new_end_time',
        'reason',
        'status',
        'requested_by',
    ];

    public function interviewSlot()
    {
        return $this->belongsTo(InterviewSlot::class, 'interview_slot_id');
    }
}
