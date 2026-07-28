<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Candidate extends Authenticatable implements JWTSubject
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'cv_file',
    ];

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
