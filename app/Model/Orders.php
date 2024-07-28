<?php

namespace App\Model;

use App\Model\PaymentPagar;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    protected $table = "orders";

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function payments_pagar(){
        return $this->hasOne(PaymentPagar::class, 'id', 'payment_id' );
    }

    public function items(){
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }

    public function enrollment(){
        return $this->hasMany(Enrollment::class, 'id', 'order_id');
    }

}

