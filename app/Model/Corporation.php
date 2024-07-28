<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Model\Orders;

class Corporation extends Model
{

    public function students()
    {
        return $this->hasMany(Student::class, 'corporation_id', 'id');
    }
}
