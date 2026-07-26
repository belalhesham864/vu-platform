<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = [
        'candidate_id',
        'position_id',
        'application_type',
        'status',
        'decision',
        'decision_date',
        'start_date',
        'approved_by',
    ];

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    public function interviews()
    {
        return $this->hasMany(Interview::class);
    }

    public function cvAnalysis()
    {
        return $this->hasOne(CvAnalysis::class);
    }

    public function feedback()
    {
        return $this->hasMany(Feedback::class);
    }

    public function flags()
    {
        return $this->hasMany(Flag::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
