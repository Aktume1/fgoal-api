<?php

namespace App\Repositories;

use App\Contracts\Repositories\GroupRepository;
use App\Eloquent\Group;
use App\Eloquent\User;
use App\Exceptions\Api\NotFoundException;

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
        $group = $this->where('id', $groupId)->first();
        $list = $group->parentGroup;
        if(!$list){
            throw new NotFoundException();
        }
        
        return $list;
    }

    /**
     * @param $userId
     * @return mixed
     */
    public function getParentsOfUser($userId)
    {
        $list = User::findOrFail($userId)->groups()->get();
        
        return $list;
    }

    /**
     * Get Infomation Group
     *
     * @param  integer  $groupId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getInfomationGroup($groupId)
    {
        return $this->infomationGroup()
        ->with([
            'childGroup' => function ($q) {
                $q->infomationGroup();
            },
        ])
        ->whereId($groupId)
        ->firstOrFail();
    }

}

