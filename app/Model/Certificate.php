<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{

    protected $table = "certificates";

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function course()
    {
        return $this->hasOne(Course::class, 'id', 'course_id');
    }


}
