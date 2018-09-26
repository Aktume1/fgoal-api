<?php

namespace App\Contracts\Repositories;

interface GroupRepository extends AbstractRepository
{
    public function getParentOfGroup($groupId);

    public function getInfomationGroup($groupId, $quarterId);

    public function deleteUserFromGroup($groupId, $userId);

    public function getUserWithPer($groupId);

    public function addMember($groupId, $data);

    public function getGroupByCode($code);

    public function getProcessById($groupId);
    
    public function getLogGroup($groupId);

    public function getLogsGroup($groupId);

    public function checkAdminGroup($groupId, $userId);

    public function getLinkRequest($groupId);

    public function getChildGroups($groupId);

    public function getTrackingByWeek($groupId);
}
