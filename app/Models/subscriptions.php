<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class subscriptions extends Model
{
    protected $fillable = [
        'company_id',
        'plan_id',
        'starts_at',
        'ends_at',
        'status',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
