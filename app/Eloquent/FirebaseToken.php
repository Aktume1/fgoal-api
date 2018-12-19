<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Eloquent\User;

class FirebaseToken extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'uuid',
        'token',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
