<?php

namespace App\Repositories;

use App\Contracts\Repositories\ActivityLogRepository;
use App\Eloquent\ActivityLog;
use App\Eloquent\Group;
use App\Eloquent\Objective;
use App\Eloquent\User;

class ActivityLogRepositoryEloquent extends AbstractRepositoryEloquent implements ActivityLogRepository
{
    public function model()
    {
        return app(ActivityLog::class);
    }

    /**
     * Get logs of group
     *
     * @param int $groupId
     * @return $group
    */
    public function getLogsByGroupId($groupId)
    {
        $group = Group::select('id', 'name')->findOrFail($groupId);
        $logs = $this->where('group_id', $groupId)->get();
        $groupLogs = null;

        foreach ($logs as $log) {
            $user = User::where('id', $log->user_id)->first();
            $user->makeHidden('token_verification');

            $actionUser = null;

            $oldValues = json_decode($log->old_values, true);
            $newValues = json_decode($log->new_values, true);
            $oldDiff = array_diff($oldValues, $newValues);
            $newDiff = array_diff($newValues, $oldValues);

            if ($log->event == Objective::CREATE) {
                $actionUser = Objective::CREATE . ' ' . $newValues['objectiveable_type'] . ' ' . $newValues['name'];
            
            } elseif ($log->event == Objective::UPDATE) {
                $actionUser = Objective::UPDATE . ' ' . $newValues['objectiveable_type'] . ' ' . $newValues['name'];
                $keyArr = array_keys($oldDiff);

                foreach ($oldDiff as $key => $value) {
                    if (end($keyArr) != $key) {
                        $actionUser .= " $key = $oldDiff[$key] to $newDiff[$key],";
                    } else {
                        $actionUser .= " $key = $oldDiff[$key] to $newDiff[$key]";
                    }
                }

            } elseif ($log->event == Objective::DELETE) {
                $actionUser = Objective::DELETE . ' ' . $newValues['objectiveable_type'] . ' ' . $newValues['name'];
            
            } elseif ($log->event == Objective::LINK) {
                $keyResultLinked = Objective::where('id', $newValues['parent_id'])->first();
                $actionUser = Objective::LINK . ' ' . $newValues['objectiveable_type'] . ' ' . $newValues['name'] . ' to '  . Objective::KEYRESULT . ' ' . $keyResultLinked->name;
            }

            $log->makeHidden('old_values');
            $log->makeHidden('new_values');
            $log->setAttribute('action', $actionUser);
            $log->setAttribute('objective_id', $newValues['id']);
            $user->setAttribute('log', $log);

            $groupLogs[] = $user;
        }

        $group->setAttribute('group_logs', $groupLogs);

        return $group;
    }
}
