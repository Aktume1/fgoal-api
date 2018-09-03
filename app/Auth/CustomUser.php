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
     * Get User of the currently authenticated user.
     *
     * @return $user|null
     */
    public function user()
    {
        return $this->user;
    }
    /**
    * Get ID of the currently authenticated user.
    *
    * @return $id|null
    */
    public function id()
    {
        return $this->user->id;
    }
}
