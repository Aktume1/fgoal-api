<?php

namespace App\Repositories;

use App\Eloquent\Objective;
use App\Contracts\Repositories\ObjectiveRepository;
use App\Exceptions\Api\NotFoundException;
use App\Exceptions\Api\UnknownException;
use Auth;
use App\Eloquent\Comment;

class ObjectiveRepositoryEloquent extends AbstractRepositoryEloquent implements ObjectiveRepository
{
    public function model()
    {
        return app(Objective::class);
    }

    public function checkUserIsGroupManager($groupId)
    {
        $this->setGuard('fauth');
        if (!$this->user->isGroupManager($groupId)) {
            throw new UnknownException(translate('http_message.unauthorized'));
        }

        return;
    }

    /**
     * Create Objective
     * @param int $groupId
     * @param array $data
     * @return Objective
     */
    public function create($groupId, $data)
    {
        $this->checkUserIsGroupManager($groupId);

        if (!isset($data['parent_id'])) {
            $data['parent_id'] = null;
            $data['objective_type'] = 'Objective';
        } else {
            $data['objective_type'] = 'Key Result';
        }
        if (!isset($data['description'])) {
            $data['description'] = null;
        }
        $objective = $this->model()->create([
            'name' => $data['name'],
            'objectiveable_type' => $data['objective_type'],
            'group_id' => $groupId,
            'description' => $data['description'],
            'unit_id' => $data['unit_id'],
            'quarter_id' => $data['quarter_id'],
            'parent_id' => $data['parent_id'],
        ]);

        if ($data['objective_type'] != Objective::OBJECTIVE) {
            $this->caculateObjectiveFromChild($groupId, $objective->id);
        }

        return $this->find($objective->id);
    }

    /**
     * Get Objective and key by Group Id
     * @param int $groupId
     */
    public function getObjective($groupId)
    {
        return $this->isObjective()->where('group_id', $groupId)->with('childObjective');
    }

    /**
     * Return all Objective in group
     * @return Objective
     */
    public function getObjectiveByGroup($groupId)
    {
        return $this->getObjective($groupId)->get();
    }

    /**
     * Get Objective and key by Group Id and Quater Id
     * @return Objective
     */
    public function getObjectiveByQuarter($groupId, $quarterId)
    {
        return $this->getObjective($groupId)->where('quarter_id', $quarterId)->get();
    }

    /**
     * Update Objective's Actual
     * @param int $groupId
     * @param int $objectiveId
     * @param array $data
     * @return Objective
     */
    public function updateObjectiveActual($groupId, $objectiveId, $data)
    {
        $this->checkUserIsGroupManager($groupId);

        $objective = $this->where('id', $objectiveId)
            ->where('group_id', $groupId)
            ->firstOrFail();

        $objective->update([
            'actual' => $data['actual'],
            'match' => Objective::UNMATCH,
        ]);

        return $this->caculateObjectiveFromChild($groupId, $objective->id);
    }

    /**
     * Caculate Objective's Actual
     * @param int $objectiveId
     * @return Objective
     */
    public function caculateObjectiveFromChild($groupId, $objectiveId)
    {
        $objective = $this->withTrashed()->findOrFail($objectiveId);
        $parentObjective = $objective->parentObjective;

        if (!$parentObjective) {
            return $objective;
        }

        $sum = $parentObjective->childObjective->sum(function ($objective) {
            return $objective->actual * $objective->weight;
        });

        $childObjectiveCount = $parentObjective->childObjective->count();
        if ($childObjectiveCount == 0) {
            $estimate = 0;
        } else {
            $estimate = (int)($sum / $parentObjective->childObjective->count());
        }

        if ($parentObjective->match != Objective::MATCH) {
            $parentObjective->update([
                'estimate' => $estimate
            ]);

            return $parentObjective;
        }

        $parentObjective->update([
            'estimate' => $estimate,
            'actual' => $estimate,
        ]);

        return $parentObjective;
    }

    /**
     * Link Objective To Key Result
     *
     * @param int $objectiveId
     * @param array $data
     * @return Objective
     */
    public function linkObjectiveToKeyResult($groupId, $data)
    {
        $objective = $this->isObjective()
            ->where('id', $data['objectiveId'])
            ->firstOrFail();

        $keyResult = $this->isKeyResult()
            ->where('id', $data['keyResultId'])
            ->firstOrFail();

        $objective->update([
            'parent_id' => $keyResult->id,
            'status' => Objective::WAITING,
            'link' => $data['link'],
        ]);

        return $objective;
    }

    /**
     * Manger verify objective linked
     *
     * @param int $groupId
     * @param in t$objectiveId
     * @return Objetive
     * @throws UnknownException
     */
    public function verifyLink($groupId, $objectiveId)
    {
        $this->checkUserIsGroupManager($groupId);

        $objective = $this->findOrFail($objectiveId);

        $objective->update([
            'status' => Objective::APPROVE,
        ]);

        $this->caculateObjectiveFromChild($groupId, $objectiveId);

        return $objective->parentObjective;
    }

    /**
     * Update link objective to null
     *
     * @param int $groupId
     * @param int $objectiveId
     * @return Objective
     * @throws UnknownException
     */
    public function removeLinkedObjective($groupId, $objectiveId)
    {
        $this->checkUserIsGroupManager($groupId);

        $objective = $this->findOrFail($objectiveId);

        if ($objective->status == Objective::APPROVE) {
            return $objective;
        }

        $objective->update([
            'status' => Objective::CANCEL,
            'parent_id' => null,
            'link' => null,
        ]);

        return $objective;
    }

    /**
     * Update content objective
     *
     * @param int $objectiveId
     * @param int $data
     * @return Objective
     */
    public function updateContent($objectiveId, $groupId, $data)
    {
        $this->checkUserIsGroupManager($groupId);

        $objective = $this->where('group_id', $groupId)
            ->findOrFail($objectiveId);

        $objective->update([
            'name' => $data,
        ]);

        return $objective;
    }

    /**
     * Match Actual With Estimate
     * @param int $groupId
     * @param int $objectiveId
     * @return Objective
     */
    public function matchActualWithEstimate($groupId, $objectiveId)
    {
        $this->checkUserIsGroupManager($groupId);

        $objective = $this->where('id', $objectiveId)
            ->where('group_id', $groupId)
            ->firstOrFail();

        $objective->update([
            'actual' => $objective->estimate,
            'match' => Objective::MATCH,
        ]);

        return $objective;
    }

    /**
     * Show detail Objective
     * @param int $groupId
     * @param int $objectiveId
     * @return void
     */
    public function showObjectiveDetail($groupId, $objectiveId)
    {
        $objective = $this->with('childObjective')
            ->where('id', $objectiveId)
            ->where('group_id', $groupId)
            ->firstOrFail();

        return $objective;
    }

    /**
     * Delete Objective
     * @param int $groupId
     * @param int $objectiveId
     * @return void
     */
    public function deleteObjective($groupId, $objectiveId)
    {
        $this->checkUserIsGroupManager($groupId);

        $objective = $this->where('id', $objectiveId)
            ->where('group_id', $groupId)
            ->firstOrFail();


        if ($this->checkParentObjective($objectiveId)) {
            $childObject = $objective->childObjective;
            foreach ($childObject as $child) {
                $child->delete();
            }
        }

        $objective->delete();

        if ($objective->objectiveable_type != Objective::OBJECTIVE) {
            $this->caculateObjectiveFromChild($groupId, $objectiveId);
        }
    }

    public function checkParentObjective($objectiveId)
    {
        $parentId = $this->pluck('parent_id')->toArray();
        if (in_array($objectiveId, $parentId)) {
            return $objectiveId;
        }

        return false;
    }

    /**
     * Get list Objective log
     * @param int $groupId
     * @return Objective
     */
    public function getObjectiveLogById($groupId, $objectiveId)
    {
        $objective = $this->where('id', $objectiveId)
            ->where('group_id', $groupId)
            ->firstOrFail()
            ->audits;

        return $objective;
    }
}
