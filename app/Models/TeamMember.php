<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'role',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
