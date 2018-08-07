<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Objective extends Model
{
    protected $fillable = [
        'is_private',
        'name',
        'description',
        'status',
        'actual',
        'estimate',
        'parent_id',
        'unit_id',
        'group_id',
        'quarter_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

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

    public function quarter()
    {
        return $this->belongsTo(Quarter::class, 'quarter_id', 'id');
    }
}
