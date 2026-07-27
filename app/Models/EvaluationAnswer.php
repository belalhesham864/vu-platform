<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluationAnswer extends Model
{
    protected $fillable = [
        'evaluation_id',
        'question',
        'answer',
    ];

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }
}
