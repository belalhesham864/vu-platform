<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class subscribers extends Model
{
    use Notifiable;
    protected $fillable = [
        'email'
    ];

    public function routeNotificationForMail($notification)
    {
        return $this->email;
    }
}
