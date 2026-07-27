<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PositionStage extends Model
{
    protected $fillable = [
        'position_id',
        'name',
        'description',
        'order',
    ];

    public function position()
    {
        return $this->belongsTo(Position::class);
    }
}
