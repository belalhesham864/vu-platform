<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterviewSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'interview_id',
        'date',
        'start_time',
        'end_time',
        'is_booked',
    ];

    public function interview()
    {
        return $this->belongsTo(Interview::class);
    }

    public function reschedule()
    {
        return $this->hasMany(InterviewReschedule::class);
    }
}
