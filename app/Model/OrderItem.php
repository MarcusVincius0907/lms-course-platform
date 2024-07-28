<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = "orderitem";

    public function order(){
        return $this->belongsTo(Order::class, 'order_id', 'id' );
    }
    
    public function course()
    {
        return $this->hasOne(Course::class, 'id', 'course_id');
    }
}
