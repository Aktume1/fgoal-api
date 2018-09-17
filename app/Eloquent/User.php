<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'code',
        'birthday',
        'gender',
        'phone',
        'mission',
        'manager_id',
        'avatar',
        'token_verification',
        'status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'created_at',
        'updated_at',
        'deleted_at',
        'remember_token',
    ];

    public function setPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['password'] = bcrypt($value);
        }
    }

    public function objectives()
    {
        return $this->belongsToMany(Objective::class, 'objective_user', 'user_id', 'objective_id')
            ->withPivot('progress')->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany(Objective::class, 'comments');
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_user', 'user_id', 'group_id')
            ->withPivot('manager');
    }

    public function workspaces()
    {
        return $this->belongsToMany(Workspace::class);
    }

    /**
     * Check if user is group's manager
     * @param int $groupId
     */
    public function isGroupManager($groupId)
    {
        if (!$this->groups()->where('group_id', $groupId)->exists() ||
            !$this->groups()->where('group_id', $groupId)->firstOrFail()->pivot->manager) {
            return false;
        }
        return true;
    }
}
