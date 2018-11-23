<?php

namespace App\Contracts\Repositories;

interface ActivityLogRepository extends AbstractRepository
{
    public function getLogsByGroupId($groupId);
}
