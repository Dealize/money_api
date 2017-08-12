<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    //
    protected $table = 'wallet';
    public $timestamps = true;
    protected $hidden = [
        'updated_at',
//        'id',
        'created_at',
        'updated_at',
        'user_id',
    ];
}
