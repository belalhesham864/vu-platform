<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
        protected $table = 'plans';

     protected $fillable = ['name','slug','price','currency','duration_days','description','is_custom','is_active' ,'stripe_price_id'];
       public function features(){
        return $this->hasMany(plan_features::class);
       }

}
