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

    public function flattenArrayLog($array)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $keyV => $valueV) {
                    $array[$key . '_' . $keyV] = $valueV;
                }
                unset($array[$key]);
            }
        }

        return $array;
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

        $logs = $this->getLogs($logs);

        $group->setAttribute('group_logs', $logs);

        return $group;
    }

    /**
     * Get logs of system
     *
     * @return $log
    */
    public function getAllLog()
    {
        $logAll = $this->get();
        $logs = $this->getLogs($logAll);

        return $logs;
    }


    /**
     * Get logs from log param
     *
     * @return $arrLog
    */
    private function getLogs($logs)
    {
        $arrLog = null;

        foreach ($logs as $log) {
            $user = User::where('id', $log->user_id)->first();
            $user->makeHidden('token_verification');

            $actionUser = null;

            $oldValues = $this->flattenArrayLog(json_decode($log->old_values, true));
            $newValues = $this->flattenArrayLog(json_decode($log->new_values, true));

            $oldDiff = array_diff_assoc($oldValues, $newValues);
            $newDiff = array_diff_assoc($newValues, $oldValues);

            if ($log->event == Objective::CREATE) {
                $actionUser = Objective::CREATE . ' ' . $newValues['objectiveable_type'] . ' ' . $newValues['name'];
            
            } elseif ($log->event == Objective::UPDATE) {
                $actionUser = Objective::UPDATE . ' ' . $newValues['objectiveable_type'] . ' ' . $newValues['name'];
                $keyArr = array_keys($oldDiff);

                foreach ($oldDiff as $key => $value) {
                    if (end($keyArr) != $key) {
                        $actionUser .= " $key from $oldDiff[$key] to $newDiff[$key],";
                    } else {
                        $actionUser .= " $key from $oldDiff[$key] to $newDiff[$key]";
                    }
                }

            } elseif ($log->event == Objective::DELETE) {
                $actionUser = Objective::DELETE . ' ' . $oldValues['objectiveable_type'] . ' ' . $oldValues['name'];
            
            } elseif ($log->event == Objective::LINK) {
                $keyResultLinked = Objective::where('id', $newValues['parent_id'])->first();
                $actionUser = Objective::LINK . ' ' . $newValues['objectiveable_type'] . ' ' . $newValues['name'] . ' to '  . Objective::KEYRESULT . ' ' . $keyResultLinked->name;
            }

            $log->makeHidden('old_values');
            $log->makeHidden('new_values');
            $log->setAttribute('action', $actionUser);

            $objectiveId = empty($oldValues) ? $newValues['id'] : $oldValues['id'];

            $log->setAttribute('objective_id', $objectiveId);
            $user->setAttribute('log', $log);
            $arrLog[] = $user;
        }

        return $arrLog;
    }
}
