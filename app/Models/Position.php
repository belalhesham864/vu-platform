<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $fillable = [
        'company_id',
        'category_id',
        'title',
        'description',
        'requirements',
        'work_location',
        'salary',
        'employment_type',
        'status',
        'approved_by'
    ];

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function company()
    {
        return $this->belongsTo(User::class, 'company_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function stages()
    {
        return $this->hasMany(PositionStage::class);
    }
}
