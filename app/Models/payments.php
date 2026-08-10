<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class payments extends Model
{
    protected $fillable = [
        'company_id',
        'plan_id',
        'stripe_payment_intent_id',
        'stripe_subscription_id',
        'amount',
        'status',
        'paid_at',
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
