<?php

namespace App\Models;
use Cviebrock\EloquentSluggable\Sluggable;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{    
    use Sluggable;
protected $fillable = ['company_name','slug','industry','location','about','phone','logo','website','company_size','status'];
        public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'company_name'
            ]
        ];
    }
    public function users(){
        return $this->hasMany(User::class);
    }
    public function owner()
{
    return $this->hasOne(User::class)->role('Owner');
}
}
