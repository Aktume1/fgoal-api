<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $fillable = [
        'type',
        'user_id',
        'group_id',
        'logable_id',
        'action',
        'property',
        'old_value',
        'new_value',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
