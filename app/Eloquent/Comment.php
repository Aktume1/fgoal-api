<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'user_id',
        'content',
        'objective_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function objective()
    {
        return $this->belongsTo(Objective::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
