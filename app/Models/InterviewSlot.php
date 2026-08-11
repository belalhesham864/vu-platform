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
    ];

    public function interview()
    {
        return $this->belongsTo(Interview::class);
    }

    public function reschedules()
    {
        return $this->hasMany(InterviewReschedule::class , 'slot_id');
    }
}
