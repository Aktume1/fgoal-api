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
     * @return $list parent group
     * @throws NotFoundException
     */
    public function getParentOfGroup($groupId)
    {
        $group = $this->findOrFail($groupId);
        $type = $group->type;

        if ($type == Group::DEFAULT_GROUP) {
            $parent = $group->parentGroup;
            $parentName = $group->parentGroup->name;
            $list = $parent;
            $this->getlinkParent($parent, $list);

            $parents = $list['parent_path'];

            $pathFinal = [];

            return $list->setAttribute('parent_final', $this->showLink($parents, $parentName));
        }

        $userCode = $group->code;
        $userId = User::where('code', $userCode)->firstOrFail()->id;
        $list = User::findOrFail($userId)->groups()->get();

        foreach ($list as $item) {
            $listGroup = $item;
            $this->getlinkParent($item, $listGroup);

            $parents = $item['parent_path'];
            $pathFinal = [];

            $item->setAttribute('parent_final', $this->showLink($parents, $item->name));
        }

        return $list;
    }

    public function getlinkParent($group, $list)
    {
        $path = [];

        while ($group->parentGroup) {
            $path[] = $group->parentGroup->only(['id', 'name']);
            $group = $group->parentGroup;
        }

        return $list->setAttribute('parent_path', $path);
    }

    public function showLink($parents, $parentName)
    {
        $string = '';
        $count = count($parents);
        for ($i = $count - 1; $i >= 0; $i--) {
            $string .= $parents[$i]['name'] . '/';
        }

        $string .= $parentName;
        $pathFinal[] = $string;

        return $pathFinal;
    }

    /**
     * Get Infomation Group
     *
     * @param  integer $groupId
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
     * @param  integer $userId
     * @param integer $groupId
     * @return void
     */
    public function deleteUserFromGroup($groupId, $userId)
    {
        $this->checkUserIsGroupManager($groupId);
        $group = $this->find($groupId);

        $group->users()->detach($userId);
    }
}

