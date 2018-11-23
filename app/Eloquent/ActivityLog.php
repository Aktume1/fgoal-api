<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;
use \Carbon\Carbon;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';

    protected $fillable = [
        'user_id',
        'group_id',
        'type',
        'event',
        'old_values',
        'new_values',
    ];

    protected $hidden = [
        'updated_at',
    ];

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->diffForHumans();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }
}
