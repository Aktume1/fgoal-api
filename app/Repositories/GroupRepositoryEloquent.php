<?php

namespace App\Repositories;

use App\Eloquent\Group;
use App\Eloquent\Log;
use App\Eloquent\Objective;
use App\Eloquent\Quarter;
use App\Eloquent\Tracking;
use App\Eloquent\User;
use App\Contracts\Repositories\GroupRepository;
use App\Exceptions\Api\NotFoundException;
use App\Exceptions\Api\UnknownException;
use Auth;
use DB;
use Carbon\Carbon;

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
     * Get parent of group or list parent of user
     *
     * @param int $groupId
     * @return $list parent group
     * @throws NotFoundException
     */
    public function getParentOfGroup($groupId)
    {
        $group = $this->findOrFail($groupId);
        $type = $group->type;

        if ($type == Group::DEFAULT_GROUP) {
            $parent = $group->parentGroup;
            if ($parent) {
                $listGroup = $parent;
                $parentName = $group->parentGroup->name;

                $this->getlinkParent($parent, $listGroup);

                $parents = $listGroup['parent_path'];
                $listGroup->setAttribute('link', [$this->showLink($parents, $parentName)]);
                $listGroup->makeHidden('parent_path');

                $list[] = (object)($listGroup);
            } else {
                $list= [];
            }

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

        return $string;
    }

    /**
     * Get Infomation Group
     *
     * @param  integer $groupId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getInfomationGroup($groupId, $quarterId)
    {
        $group = $this->findOrFail($groupId);
        $user = $group->users;
        $weight = $avg = 0;
        foreach ($user as $row) {
            $checkManager = User::findOrFail($row->id)->isGroupManager($groupId);

            if ($checkManager) {
                $row->setAttribute('role', User::ADMIN);
            } else {
                $row->setAttribute('role', User::MEMBER);
            }

            $groupUser = $this->where('code', $row->code)->first();

            foreach ($groupUser->objectives as $obj) {

                if ($obj->objectiveable_type == Objective::OBJECTIVE && $obj->quarter_id == $quarterId) {
                    $weight += $obj->weight;
                    $avg += $obj->actual * $obj->weight;
                }
            }

            if (count($groupUser->objectives) == 0) {
                $row->setAttribute('process', 0);
            } else {
                if ($weight == 0) {
                    $row->setAttribute('process', 0);
                } else {
                    $row->setAttribute('process', $avg / $weight);
                }
            }
        }

        return $group->setAttribute('users', $user);
    }

    public function getChildGroups($groupId)
    {
        $group = $this->findOrFail($groupId);
        $group->setAttribute('link', [$this->showLinkSearch($groupId)]);

        return $group->setAttribute('child_group', $group->childGroup);
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

        Log::create([
            'user_id' => Auth::guard('fauth')->user()->id,
            'group_id' => $groupId,
            'logable_id' => $userId,
            'action' => Objective::DELETE,
        ]);

        $group->users()->detach($userId);
    }

    public function getUserWithPer($groupId)
    {
        $users = $this->findOrFail($groupId)->users()->get();

        return $users;
    }

    /**
     * Add member to group
     *
     * @param $groupId
     * @param $data
     * @return mixed
     * @throws UnknownException
     */
    public function addMember($groupId, $data)
    {
        $this->checkUserIsGroupManager($groupId);

        $group = $this->findOrFail($groupId);

        $email = $data['email'];
        $user = User::where('email', $email)->get();

        Log::create([
            'user_id' => Auth::guard('fauth')->user()->id,
            'group_id' => $groupId,
            'logable_id' => $user->id,
            'action' => Objective::CREATE,
        ]);

        $group->users()->detach($user);
        $group->users()->attach($user, ['manager' => $data['role']]);

        return $group;
    }

    /**
     * Get group by emloyee code
     *
     * @param string $employeeCode
     * @return $groupUser
     */
    public function getGroupByCode($employeeCode)
    {
        $groupUser = $this->where('code', $employeeCode)->firstOrFail();

        return $groupUser;
    }

    /**
     * Get process of group
     *
     * @param int $groupId
     * @return array
     */
    public function getProcessById($groupId, $quarterId)
    {
        $off = $inprocess = $done = 0;
        $totalObjectives = 0;

        $objectives = Objective::isObjective()->where('group_id', $groupId)
                    ->where('quarter_id', $quarterId)
                    ->get();
                    
        foreach ($objectives as $objective) {
            if ($objective->objectiveable_type == Objective::OBJECTIVE) {
                $processObjective = $objective->actual;

                if ($processObjective == Objective::PROCESS_OFF) {
                    $off += 1;
                } elseif ($processObjective == Objective::PROCESS_DONE) {
                    $done += 1;
                } else {
                    $inprocess += 1;
                }

                $totalObjectives += 1;
            }
        }

        if ($totalObjectives > 0) {
            $off = $off / $totalObjectives;
            $inprocess = $inprocess / $totalObjectives;
            $done = $done / $totalObjectives;

            $process = [$off, $inprocess, $done];
        } else {
            $noObjecitve = Objective::NO_OBJECTIVE;
            $process = [$noObjecitve, $noObjecitve, $noObjecitve];
        }

        return $process;
    }

    /**
     * Get log of group
     *
     * @param int $groupId
     * @return $group
     */
    public function getLogGroup($groupId)
    {
        $group = $this->findOrFail($groupId)
            ->audits;

        return $group;
    }

    /**
     * Get logs of group
     *
     * @param int $groupId
     * @return $group
     */
    public function getLogsGroup($groupId)
    {
        $logs = Log::where('group_id', $groupId)->get();
        foreach ($logs as $row) {
            $objective = Objective::findOrFail($row->logable_id);
            $name = $objective->name;
            $row->setAttribute('objective_name', $name);
        }

        return $logs;
    }

    public function checkAdminGroup($groupId, $userId)
    {
        $user = User::findOrFail($userId);

        $check = $user->isGroupManager($groupId);

        return $check;
    }

    public function getLinkRequest($groupId)
    {
        $group = $this->findOrFail($groupId);

        $list = [];
        foreach ($group->objectives as $objective) {

            if ($objective->objectiveable_type == Objective::KEYRESULT) {
                $array = Objective::where('parent_id', $objective->id)->where('status', Objective::WAITING)->with('group')->get();

                if (count($array) > 0) {
                    $objective->setAttribute('parent_objective', $objective->parentObjective);
                    $list[] = $objective->setAttribute('link_request', $array);

                }
            }
        }

        return $list;
    }

    public function getTrackingByWeek($groupId, $quarterId)
    {
        $time = Quarter::where('id', $quarterId)->first();
        $data = [];

        $trackings = DB::table('group_tracking')
            ->where('group_id', $groupId)
            ->whereBetween('date', [$time['start_date'], $time['end_date']])
            ->get();

        foreach ($trackings as $key => $tracking) {
            $data[$key]['progress'] = $tracking->actual;
            $data[$key]['updated_at'] = $tracking->date;
        }

        return $data;
    }

    public function trackingByWeek()
    {
        $month = Carbon::today()->month;
        if (0 < $month && $month < 4) {
            $quarterId = 1;
        } elseif (3 < $month && $month < 7) {
            $quarterId = 2;
        } elseif (6 < $month && $month < 10) {
            $quarterId = 3;
        } else {
            $quarterId = 4;
        }

        $time = Quarter::where('id', $quarterId)->first();

        $trackings = DB::table('group_tracking')
                ->whereBetween('date', [$time['start_date'], $time['end_date']])
                ->get();

        $groups = $this->where('type', Group::USER_GROUP)->get();
        foreach ($groups as $group) {
            $actual = $this->getActual($group->id);

            $group->trackings()->attach(
                count($trackings) + 1,
                [
                    'actual' => $actual,
                    'date' => Carbon::today()->toDateString(),
                ]
            );
        }
    }

    public function getActual($groupId)
    {
        $objectives = Objective::isObjective()->where('group_id', $groupId)->get();
        $weight = $avg = $data = 0;
        foreach ($objectives as $obj) {
            if ($obj->objectiveable_type == Objective::OBJECTIVE) {
                $weight += $obj->weight;
                $avg += $obj->actual * $obj->weight;
            }
        }

        if ($weight > 0) {
            $data = $avg / $weight;
        }

        return $data;
    }

    public function getGroupBySearchName($name)
    {
        $groups = $this->where('name', 'like', $name . '%')->get();
        
        for ($i = 0; $i < count($groups); $i++) {
            $data[$i] = $groups[$i];
            $link = $this->showLinkSearch($groups[$i]->id);

            if (!isset($link)) {
                $data[$i]['link'] =  $groups[$i]->name;
            } else {
                $data[$i]['link'] = $this->showLinkSearch($groups[$i]->id) . '/' . $groups[$i]->name;
            }

            $lengthOfLink = strlen($data[$i]['link']);
            $data[$i]['link'] = str_split($data[$i]['link'], $lengthOfLink);
        }

        return $data;
    }

    public function showLinkSearch($groupId)
    {
        $group = $this->findOrFail($groupId);
        $type = $group->type;
        if ($type == Group::DEFAULT_GROUP) {
            $parent = $group->parentGroup;
            $listGroup = $parent;

            if (!isset($group->parentGroup)) {
                return null;
            }
            
            $parentName = $group->parentGroup->name;

            $this->getlinkParent($parent, $listGroup);

            $parents = $listGroup['parent_path'];

            return $this->showLink($parents, $parentName);
        }

        $userCode = $group->code;
        $userId = User::where('code', $userCode)->firstOrFail()->id;
        // get list group of group has id = $groupId except it
        $list = User::findOrFail($userId)->groups()->get()->except($groupId);

        foreach ($list as $item) {
            $listGroup = $item;

            $this->getlinkParent($item, $listGroup);

            $parents = $item['parent_path'];

            return $this->showLink($parents, $item->name);
        }
    }
}
