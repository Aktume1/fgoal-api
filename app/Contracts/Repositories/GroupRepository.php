<?php

namespace App\Contracts\Repositories;

interface GroupRepository extends AbstractRepository
{
    public function getParentOfGroup($groupId);

    public function getInfomationGroup($groupId);

    public function deleteUserFromGroup($groupId, $userId);
}
