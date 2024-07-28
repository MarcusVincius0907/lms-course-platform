<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $table = "coupons";

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function course()
    {
        return $this->hasOne(Course::class, 'id', 'course_id');
    }

}

