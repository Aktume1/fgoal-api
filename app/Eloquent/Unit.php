<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = [
        'unit',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function objectives()
    {
        return $this->hasMany(Objective::class, 'unit_id', 'id');
    }
}
