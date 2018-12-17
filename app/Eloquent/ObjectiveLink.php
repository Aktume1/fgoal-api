<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ObjectiveLink extends Model
{
    use SoftDeletes;

    const CANCEL = 0;
    const WAITING = 1;
    const APPROVE = 2;

    protected $table = 'objective_links';
    protected $fillable = [
        'key_result_id',
        'objective_id',
        'status',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function objective()
    {
        return $this->belongsTo(Objective::class, 'objective_id', 'id');
    }
}
