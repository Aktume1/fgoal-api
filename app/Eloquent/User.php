<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Eloquent\Group;
use App\Eloquent\Workspace;
use DB;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    const ADMIN = 1;
    const MEMBER = 0;

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
        return $this->belongsToMany(Objective::class, 'objective_user', 'user_id', 'objective_id')->withPivot('type');
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
        return $this->belongsToMany(Workspace::class)->withPivot('is_manager');
    }

    public function logs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Check if user is group's manager
     * @param int $groupId
     */
    public function isGroupManager($groupId)
    {
        $group = Group::findOrFail($groupId);
        $arrUserInGroup = DB::table('group_user')->where('group_id', $groupId)->pluck('user_id')->toArray();

        $parentPath = $group->parent_path;
        $workSpaces = $this->workspaces;

        $userFromPath = $parentPath ? $this->getUserFromParentPath($parentPath) : null;
        $userFromWorkspace = $workSpaces ? $this->getUserFromWorkspace($workSpaces) : null;

        if (in_array($this->id, $arrUserInGroup)) {
            return true;
        }
        // If userId logged is Admin
        if (isset($userFromWorkspace) && in_array($this->id, $userFromWorkspace)) {
            return true;
        
        // Is if userId logged in array userId from parent_path
        } elseif ($group->type != Group::USER_GROUP && isset($userFromPath) && in_array($this->id, $userFromPath)) {
            return true;

        // Is group user
        } elseif ($group->type == Group::USER_GROUP && !isset($userFromPath) && $group->code == $this->code) {
            return true;
        }

        return false;
    }

    public function getUserFromWorkspace($workSpaces)
    {
        $workspacesUserId = [];
        foreach ($workSpaces as $workSpace) {
            if ($workSpace->pivot->is_manager == config('model.workspace.is_manager')) {
                $workspacesUserId[] = $workSpace->pivot->user_id;
            }
        }
        
        return $workspacesUserId;
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
            foreach ($groupValue->users as $userValue) {
                $userArr[] = $userValue->id;
            }
        }

        return $userArr;
    }
}
