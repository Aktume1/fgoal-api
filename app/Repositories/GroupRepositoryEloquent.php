<?php

namespace App\Repositories;

use App\Eloquent\Group;
use App\Eloquent\Objective;
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
            $listGroup = $parent;
            $parentName = $group->parentGroup->name;

            $this->getlinkParent($parent, $listGroup);

            $parents = $listGroup['parent_path'];
            $listGroup->setAttribute('link', $this->showLink($parents, $parentName));

            $listGroup->makeHidden('parent_path');

            $list[] = (object)($listGroup);

            return $list;
        }

        $userCode = $group->code;
        $userId = User::where('code', $userCode)->firstOrFail()->id;
        // get list group of group has id = $groupId except it
        $list = User::findOrFail($userId)->groups()->get()->except($groupId);

        foreach ($list as $item) {
            $listGroup = $item;

            $this->getlinkParent($item, $listGroup);

            $parents = $item['parent_path'];
            $item->setAttribute('link', $this->showLink($parents, $item->name));

            $item->makeHidden('parent_path');
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

    //create link parent
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

    public function getUserWithPer($groupId)
    {
        $users = $this->findOrFail($groupId)->users()->get();

        return $users;
    }

    public function addMember($groupId, $data)
    {
        $group = $this->findOrFail($groupId);

        $email = $data['email'];
        $user = User::where('email', $email)->get();

        $group->users()->detach($user);
        $group->users()->attach($user, ['manager' => $data['role']]);
    }

    public function getGroupByCode($employeeCode)
    {
        $groupUser = $this->where('code', $employeeCode)->firstOrFail();

        return $groupUser;
    }

    public function getProcessById($groupId)
    {
        $off = $inprocess = $done = 0;

        $objectives = Objective::isObjective()->where('group_id', $groupId)->get();

        foreach ($objectives as $objective) {
            if ($objective->process == config('model.objective.process.off')) {
                $off += 1;
            } elseif ($objective->process == config('model.objective.process.inprocess')) {
                $inprocess += 1;
            } else {
                $done += 1;
            }
        }

        $totalObjectives = $objectives->count();

        $off = $off / $totalObjectives;
        $inprocess = $inprocess / $totalObjectives;
        $done = $done / $totalObjectives;

        $process = [$off, $inprocess, $done];

        return $process;
    }
    
    public function getLogGroup($groupId)
    {
        $group = $this->findOrFail($groupId)
                    ->audits;

        return $group;
    }
}
