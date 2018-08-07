<?php

namespace App\Repositories;

use App\Contracts\Repositories\GroupRepository;
use App\Eloquent\Group;

class GroupRepositoryEloquent extends AbstractRepositoryEloquent implements GroupRepository
{
    public function model()
    {
        return app(Group::class);
    }
}
