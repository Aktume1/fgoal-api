<?php

namespace App\Contracts\Repositories;

interface ObjectiveRepository extends AbstractRepository
{
    public function create($groupId, $data);

    public function isObjective();

    public function getObjective($groupId);

    public function getObjectiveByGroup($groupId);

    public function getObjectiveByQuarter($groupId, $quarterId);
}
