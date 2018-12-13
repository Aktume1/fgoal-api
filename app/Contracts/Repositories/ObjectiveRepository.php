<?php

namespace App\Contracts\Repositories;

interface ObjectiveRepository extends AbstractRepository
{
    public function create($groupId, $data);

    public function checkUserIsGroupManager($groupId);

    public function getObjective($groupId, $quarterId = null);

    public function getObjectiveByGroup($groupId);

    public function getObjectiveByQuarter($groupId, $quarterId);

    public function updateObjectiveActual($groupId, $objectiveId, $data);

    public function caculateObjectiveFromChild($groupId, $objectiveId);

    public function linkObjectiveToKeyResult($groupId, $data);

    public function verifyLink($groupId, $objectiveId);

    public function verifyAllLink($groupId, $keyResultId);

    public function removeAllLink($groupId, $keyResultId);

    public function removeLinkedObjective($groupId, $objectiveId, $data);

    public function removeLinkObjectiveAccepted($groupId, $objectiveId, $data);

    public function matchActualWithEstimate($groupId, $objectiveId);

    public function showObjectiveDetail($groupId, $objectiveId);

    public function updateName($groupId, $objectiveId, $data);

    public function updateWeight($groupId, $objectiveId, $data);

    public function updateTargetVsUnit($groupId, $objectiveId, $data);

    public function updateContent($groupId, $objectiveId, $data);

    public function deleteObjective($groupId, $objectiveId);

    public function checkParentObjective($objectiveId);

    public function getObjectiveLogById($groupId, $objectiveId);

    public function checkExpriedQuarter($quarterId, $message);
}
