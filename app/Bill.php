<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class Bill extends Model
{
    protected $hidden = [
        'updated_at',
        'id',
        'created_at',
        'user_id',
        'category_id',
        ];
}
