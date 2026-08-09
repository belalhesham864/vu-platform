<?php

namespace App\Models;

use App\Models\Position;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use Sluggable, HasFactory;
    protected $fillable = ['company_name', 'slug', 'industry', 'location', 'about', 'phone', 'logo', 'website', 'company_size', 'status', 'stripe_customer_id'];
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'company_name'
            ]
        ];
    }
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function positions()
    {
        return $this->hasMany(Position::class);
    }

    public function owner()
    {
        return $this->hasOne(User::class)->role('Owner');
    }
    public function companySubscriptions()
    {
        return $this->hasMany(subscriptions::class);
    }

    public function payments()
    {
        return $this->hasMany(payments::class);
    }
}
