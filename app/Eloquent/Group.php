<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Group extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'code',
        'name',
        'parent_id',
        'parent_path',
        'type',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    const DEFAULT_GROUP = 0;
    const USER_GROUP = 1;

    public function users()
    {
        return $this->belongsToMany(User::class, 'group_user', 'group_id', 'user_id')
            ->withPivot('manager')
            ->withTimestamps();
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
                $q->select('users.id', 'name', 'email', 'code', 'mission', 'avatar', 'status')->whereStatus(true);
            },
        ]);
    }
}
