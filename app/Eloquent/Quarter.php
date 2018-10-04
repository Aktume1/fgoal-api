<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Quarter extends Model
{
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'expried',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function objectives()
    {
        return $this->hasMany(Objective::class, 'quarter_id', 'id');
    }
}
