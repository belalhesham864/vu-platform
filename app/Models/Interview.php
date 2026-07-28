<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    protected $fillable = [
        'application_id',
        'interviewer_id',
        'available_until',
        'estimated_duration',
        'question_count',
        'status',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function interviewer()
    {
        return $this->belongsTo(User::class, 'interviewer_id');
    }

    public function slots()
    {
        return $this->hasMany(InterviewSlot::class);
    }

    public function reschedule()
    {
        return $this->hasMany(InterviewReschedule::class);
    }
}
