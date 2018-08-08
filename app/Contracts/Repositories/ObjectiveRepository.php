<?php

namespace App\Contracts\Repositories;

interface ObjectiveRepository extends AbstractRepository
{
    public function create($groupId, $data);
}
