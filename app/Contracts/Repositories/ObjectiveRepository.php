<?php

namespace App\Contracts\Repositories;

interface ObjectiveRepository extends AbstractRepository
{
    public function create($groupId, $data);

    public function getObjective($groupId);

    public function getObjectiveByGroup($groupId);

    public function getObjectiveByQuarter($groupId, $quarterId);

    public function updateObjectiveActual($groupId, $objectiveId, $data);

    public function caculateObjectiveFromChild($objectiveId);

    public function linkObjectiveToKeyResult($groupId, $data);
}
