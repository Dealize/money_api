<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SecondCategory extends Model
{
    //
    protected  $table = 'secondCategory';
    public $timestamps = true;

    public function firstCategory(){
        return $this->hasOne('App\FirstCategory','id','firstCategory_id');
    }
}
