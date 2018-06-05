<?php

namespace App\Repositories;

use App\Contracts\Repositories\UserRepository;
use App\Eloquent\User;

class UserRepositoryEloquent extends AbstractRepositoryEloquent implements UserRepository
{
    public function model()
    {
        return app(User::class);
    }

    public function checkActiveAccount($email)
    {
        return $this->model()->where('email', $email)
            ->where('status', config('model.user.status.active'))
            ->count() !== 0;
    }

    public function getUserByEmail($email, array $dataSelect = ['*'])
    {
        return $this->model()->where('email', $email)->select($dataSelect)->first();
    }
}
