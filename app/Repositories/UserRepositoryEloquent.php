<?php

namespace App\Repositories;

use App\Contracts\Repositories\UserRepository;
use App\Eloquent\User;
use App\Eloquent\Group;

class UserRepositoryEloquent extends AbstractRepositoryEloquent implements UserRepository
{
    public function model()
    {
        return app(User::class);
    }

    public function checkActiveAccount($email)
    {
        return $this->model()->where('email', $email)
            ->where('status', config('model.user.status.working'))
            ->count() !== 0;
    }

    public function getUserByEmail($email, array $dataSelect = ['*'])
    {
        return $this->model()->where('email', $email)->select($dataSelect)->first();
    }

    public function getUserByToken($token)
    {
        return $this->model()->where('token_verification', $token)->first();
    }

    public function getUserByCode($code)
    {
        $user = $this->where('code', $code)->firstOrFail();
        $user['group_user'] = Group::where('code', $user['code'])->firstOrFail();
        
        return $user;
    }
}
