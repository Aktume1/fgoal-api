<?php

namespace App\Contracts\Repositories;

interface GroupRepository extends AbstractRepository
{
    public function getParentOfGroup($groupId);

    public function getParentsOfUser($userId);

	public function getInfomationGroup($groupId);
}
