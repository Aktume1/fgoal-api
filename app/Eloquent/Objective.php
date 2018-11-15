<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Objective extends Model
{
    use SoftDeletes;

    const CANCEL = 0;
    const WAITING = 1;
    const APPROVE = 2;

    const MATCH = 1;
    const UNMATCH = 0;

    const NO_OBJECTIVE = 0;

    const OBJECTIVE = 'Objective';
    const KEYRESULT = 'Key Result';

    const PROCESS_OFF = 0;
    const PROCESS_DONE = 100;

    const CREATE = 'Create';
    const UPDATE = 'Update';
    const DELETE = 'Delete';
    
    const GROUP = 0;
    const USER = 1;

    protected $fillable = [
        'is_private',
        'name',
        'description',
        'status',
        'process',
        'match',
        'objectiveable_type',
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
        'deleted_at',
    ];

    protected $dates = ['deleted_at'];

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'objective_user', 'objective_id', 'user_id')
            ->withPivot('type')->withTimestamps();
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
        return $this->hasMany(Comment::class, 'objective_id', 'id');
    }

    public function quarter()
    {
        return $this->belongsTo(Quarter::class, 'quarter_id', 'id');
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('objectiveable_type', $type);
    }

    public function scopeIsObjective($query)
    {
        return $this->ofType('Objective');
    }

    public function scopeIsKeyResult($query)
    {
        return $this->ofType('Key Result');
    }
}
