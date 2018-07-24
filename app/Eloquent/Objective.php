<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Objective extends Model
{
    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id', 'code');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'objective_user', 'objective_id', 'user_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }
    
    public function parentObjective()
    {
        return $this->belongsTo(Objective::class, 'parent_id', 'id');
    }

    public function childObjective()
    {
        return $this->hasMany(Objective::class, 'parent_id', 'id');
    }

    public function comments()
    {
        return $this->belongsToMany(User::class, 'comments', 'objective_id', 'user_id');
    }
}
