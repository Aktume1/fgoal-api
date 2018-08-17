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
        'location',
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
        return $this->belongsToMany(Objective::class, 'comments', 'user_id', 'objective_id');
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_user', 'user_id', 'group_id');
    }
}
