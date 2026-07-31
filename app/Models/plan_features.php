<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class plan_features extends Model
{
    protected $table='plan_features';
        protected $fillable = [
        'plan_id',
        'feature',
    ];
      public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
