<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class PaymentPagar extends Model
{
    protected $table = "payments_pagar";

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'payment_id', 'id');
    }

    public function order(){
        return $this->belongsTo(Order::class, 'payment_id', 'id' );
    }
}
