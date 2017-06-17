<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IncomeSecondCategory extends Model
{
    //
    protected  $table = 'incomeSecondCategory';
    public $timestamps = true;

    public function firstCategory(){
        return $this->hasOne('App\IncomeCategory','id','firstCategory_id');
    }
}
