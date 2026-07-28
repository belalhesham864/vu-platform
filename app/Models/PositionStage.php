<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PositionStage extends Model
{
    protected $fillable = [
        'position_id',
        'stage_name',
        'stage_order',
        'is_active',
    ];

    public function position()
    {
        return $this->belongsTo(Position::class);
    }
}
