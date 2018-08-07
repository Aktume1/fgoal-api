<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Quarter extends Model
{
    public function objectives()
    {
        return $this->hasMany(Objective::class, 'quarter_id', 'id');
    }
}
