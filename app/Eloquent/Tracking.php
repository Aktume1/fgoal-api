<?php

namespace App\Eloquent;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Eloquent\Group;

class Tracking extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'quarter_id',
        'week',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_tracking', 'tracking_id', 'group_id')
            ->withPivot('actual')->withPivot('date');
    }
}
