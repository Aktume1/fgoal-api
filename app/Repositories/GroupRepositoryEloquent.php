<?php

namespace App\Repositories;

use App\Eloquent\Group;
use App\Eloquent\User;
use App\Contracts\Repositories\GroupRepository;
use App\Exceptions\Api\NotFoundException;
use App\Exceptions\Api\UnknownException;
use Auth;

class GroupRepositoryEloquent extends AbstractRepositoryEloquent implements GroupRepository
{
    public function model()
    {
        return app(Group::class);
    }

    public function checkUserIsGroupManager($groupId)
    {
        $this->setGuard('fauth');
        if (!$this->user->isGroupManager($groupId)) {
            throw new UnknownException(translate('http_message.unauthorized'));
        }

        return;
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

    /**
     * Delete User From Group
     *
     * @param  integer  $userId
     * @param integer $groupId
     * @return void
     */
    public function deleteUserFromGroup($groupId, $userId){
        $this->checkUserIsGroupManager($groupId);
        $group = $this->find($groupId);

        $group->users()->detach($userId);
    }
}

