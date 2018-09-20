<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Eloquent\Group;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    const ADMIN_ID = 1;

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
        $group = Group::findOrFail($groupId);
        $parentPath = $group->parent_path;
        $userFromPath = $parentPath ? $this->getUserFromParentPath($parentPath) : null;

        // Is group user
        if ($group->type == Group::USER_GROUP && $group->code == $this->code) {
            return true;

        // Is if userId logged in array userId from parent_path
        } elseif ($group->type != Group::USER_GROUP && isset($userFromPath) && in_array($this->id, $userFromPath)) {
            return true;

        // If userId logged is Admin
        } elseif ($group->type != Group::USER_GROUP && !isset($userFromPath) && $this->id == User::ADMIN_ID) {
            return true;
        }

        return false;
    }

    public function getUserFromParentPath($parentPath)
    {
        $parentPathArr = explode('/', $parentPath);
        $groupFromPath = [];

        foreach ($parentPathArr as $pathValue) {
            $groupFromPath[] = Group::where('code', $pathValue)->firstOrFail();
        }

        $userArr = [];
        foreach ($groupFromPath as $groupValue) {
            foreach ($groupValue->users as $value) {
                $userArr[] = $value->id;
            }
        }

        return $userArr;
    }
}
