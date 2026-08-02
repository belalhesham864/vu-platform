<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class subscriptions extends Model
{
    protected $fillable = [
        'payment_id',
        'company_id',
        'plan_id',
        'start_at',
        'end_at',
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
