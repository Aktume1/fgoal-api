<?php

namespace App\Repositories;

use App\Contracts\Repositories\GroupRepository;
use App\Eloquent\Group;
use App\Eloquent\User;

class GroupRepositoryEloquent extends AbstractRepositoryEloquent implements GroupRepository
{
    public function model()
    {
        return app(Group::class);
    }

    /**
     * @param $groupId
     * @return mixed
     */
    public function getParentOfGroup($groupId)
    {
        return $this->where('id', $groupId)->first();
    }

    /**
     * @param $userId
     * @return mixed
     */
    public function getParentsOfUser($userId)
    {
        return User::findOrFail($userId)->groups()->get();
    }
}

