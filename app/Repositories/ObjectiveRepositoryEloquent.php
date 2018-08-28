<?php

namespace App\Repositories;

use App\Eloquent\Objective;
use App\Contracts\Repositories\ObjectiveRepository;
use App\Exceptions\Api\NotFoundException;
use App\Exceptions\Api\UnknownException;
use Auth;

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
            ->where('group_id', $groupId)->firstOrFail();
        $objective->update(['actual' => $data['actual']]);
        if ($objective->status != Objective::APPROVE) {
            return $objective;
        }

        return $this->caculateObjectiveFromChild($objective->id);
    }

    /**
     * Caculate Objective's Actual
     * @param int $objectiveId
     * @return Objective
     */
    public function caculateObjectiveFromChild($objectiveId)
    {
        $objective = $this->find($objectiveId);
        $parentObjective = $objective->parentObjective;
        if (!$parentObjective) {
            return $objective;
        }
        $sum = $parentObjective->childObjective->sum(function ($objective) {
            return $objective->actual * $objective->weight;
        });
        $estimate = (int)($sum / $parentObjective->childObjective->count());
        $parentObjective->update(['estimate' => $estimate]);

        return $parentObjective;
    }

    /**
     * Link Objective To Key Result
     * @param int $objectiveId
     * @param array $data
     * @return Objective
     */
    public function linkObjectiveToKeyResult($groupId, $data)
    {
        $this->checkUserIsGroupManager($groupId);

        $objective = $this->isObjective()->where('id', $data['objectiveId'])
            ->where('group_id', $groupId)->firstOrFail();
        $keyResult = $this->isKeyResult()->where('id', $data['keyResultId'])
            ->firstOrFail();
        $objective->update([
            'parent_id' => $keyResult->id,
            'status' => Objective::WAITING,
        ]);

        return $objective;
    }

    /**
     * Update content objective
     * @param $objectiveId
     * @param $data
     * @return mixed
     */
    public function updateContent($objectiveId, $groupId, $data)
    {
        $this->checkUserIsGroupManager($groupId);

        $objective = $this->where('group_id', $groupId)->findOrFail($objectiveId);
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
            ->where('group_id', $groupId)->firstOrFail();

        $objective->update([
            'actual' => $objective->estimate,
        ]);

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
            ->where('group_id', $groupId)->firstOrFail();

        $this->caculateObjectiveFromChild($objective->id);
        
        $objective->delete();
    }
}
