<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    public function objectives()
    {
        return $this->hasMany(Objective::class, 'unit_id', 'id');
    }
}
