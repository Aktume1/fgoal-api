<?php
namespace App\Auth;

use App\Eloquent\User;

class CustomUser
{
    protected $user;
    
    /**
     * Set the current user.
     *
     * @param  \App\Eloquent\User  $user
     * @return void
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the ID for the currently authenticated user.
     *
     * @return $user|null
     */
    public function user()
    {
        return $this->user;
    }
}
