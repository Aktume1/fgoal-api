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

    public function getAll()
    {
        return $this->get();
    }

    public function create($data)
    {
        $users = $this->getAll();
        $arr = [];
        foreach ($users as $key => $value) {
            $arr[] = $value->email;
        }
        if (!$data['avatar']) {
            $data['avatar'] =  config('constant.avatar');
        }
        if (in_array($data['email'], $arr) == false) {
            $user = $this->model()->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'code' => $data['code'],
                'birthday' => $data['birthday'],
                'gender' => $data['gender'],
                'phone' => $data['phone'],
                'mission' => $data['mission'],
                'avatar' => $data['avatar'],
                'status' => config('constant.one'),
            ]); 
        }
        
        return $this->findOrFail($user->id);
    }

    public function show($id)
    {
        $user = $this->findOrFail($id);

        return $user;
    }

    public function update($id, $data)
    {
        $user = $this->findOrFail($id);
        $user->update($data);

        return $user;
    }

    public function delete($id)
    {
        $unit = $this->findOrFail($id);
        $unit->delete();

        return $unit;
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
}
