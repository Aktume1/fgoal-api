<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{

    protected $fillable = [
        'code',
        'name',
        'parent_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'group_user', 'group_id', 'user_id')
                    ->withPivot('manager');
    }
    
    public function objectives()
    {
        return $this->hasMany(Objective::class, 'group_id', 'id');
    }

    public function parentGroup()
    {
        return $this->belongsTo(Group::class, 'parent_id', 'code');
    }

    public function childGroup()
    {
        return $this->hasMany(Group::class, 'parent_id', 'code');
    }

    public function scopeInfomationGroup($query)
    {
        return $query->select('id', 'name', 'code', 'parent_id')
        ->with([
            'users' => function ($q) {
                $q->select('users.id', 'name')->whereStatus(true);
            },
        ]);
    }
}
