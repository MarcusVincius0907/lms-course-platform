<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class CertificateTemplates extends Model
{

    protected $table = "certificate_templates";

    public function instructor(){
        return $this->belongsTo(Instructor::class,'instructor_id','id');
    }


}
