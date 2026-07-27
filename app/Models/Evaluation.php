<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $fillable = [
        'application_id',
        'interview_id',
        'overall_score',
        'weaknesses',
        'strengths',
        'recording_url',
        'notes',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function answers()
    {
        return $this->hasMany(EvaluationAnswer::class);
    }
}
