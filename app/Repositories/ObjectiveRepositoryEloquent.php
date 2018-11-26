<?php

namespace App\Repositories;

use App\Eloquent\ActivityLog;
use App\Eloquent\Objective;
use App\Eloquent\Group;
use App\Contracts\Repositories\ObjectiveRepository;
use App\Exceptions\Api\NotFoundException;
use App\Exceptions\Api\UnknownException;
use Auth;
use App\Eloquent\Comment;
use App\Eloquent\Quarter;
use DB;

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

    public function checkExpriedQuarter($quarterId, $message)
    {
        $quarter = Quarter::findOrFail($quarterId);
        $expried = $quarter->expried;
        if ($expried == config('model.quarter.expried.expried')) {
            throw new UnknownException($message);
        }

        return;
    }

    public function getFullObjective($groupId, $objectiveId)
    {
        $objective = $this->where('id', $objectiveId)
                ->where('group_id', $groupId)
                ->firstOrFail();
        $parentObj = $this->where('id', $objective->parent_id)->first();

        if (isset($parentObj)) {
            $parentObj->makeHidden('group_id');
            $parentObj->setAttribute('group', $parentObj->group);
        }
        
        $objective->setAttribute('link_to', null);

        if ($objective->status != Objective::CANCEL) {
            $objective->setAttribute('link_to', $parentObj);
        }

        $objective->makeHidden('group_id');
        $objective->setAttribute('group', $objective->group);
        
        foreach ($objective->childObjective as $childs) {
            $parentObjChild = $this->where('id', $childs->parent_id)->first();
            $childs->makeHidden('group_id');
            $childs->setAttribute('link_to', $parentObjChild);
            $childs->setAttribute('group', $childs->group);

            foreach ($childs->childObjective as $child) {
                $parentChild = $this->where('id', $child->parent_id)->first();
                $child->makeHidden('group_id');
                $child->setAttribute('link_to', $parentChild);
                $child->setAttribute('group', $child->group);
            }
        }

        return $objective;
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
            $message = translate('quarter.create_objective');
        } else {
            $data['objective_type'] = 'Key Result';
            $message = translate('quarter.create_key');
        }
        $this->checkExpriedQuarter($data['quarter_id'], $message);
        
        if (!isset($data['description'])) {
            $data['description'] = null;
        }

        if (!isset($data['weight'])) {
            $data['weight'] = OBJECTIVE::WEIGHT_DEFAULT;
        }

        $objective = $this->model()->create([
            'name' => $data['name'],
            'objectiveable_type' => $data['objective_type'],
            'group_id' => $groupId,
            'description' => $data['description'],
            'weight' => $data['weight'],
            'unit_id' => $data['unit_id'],
            'quarter_id' => $data['quarter_id'],
            'parent_id' => $data['parent_id'],
        ]);

        $objectiveCreated = $this->findOrFail($objective->id);

        $userId = $this->user->id;
        $typeOfGroup = Group::findOrFail($groupId)->type;
        if($typeOfGroup == Group::TYPE_USER){
            $objective->users()->attach($userId, [
                'type' => OBJECTIVE::USER,
            ]);
        }

        $this->getLogObjective($groupId, Objective::CREATE, $data['objective_type'], [], $objectiveCreated);


        if ($data['objective_type'] != Objective::OBJECTIVE) {
            $this->caculateObjectiveFromChild($groupId, $objective->id);
        }

        return $objectiveCreated;
    }

    public function getLogObjective($groupId, $event, $type, $oldValues, $newValues)
    {
        ActivityLog::create([
            'user_id' => Auth::guard('fauth')->user()->id,
            'group_id' => $groupId,
            'event' => $event,
            'type' => $type,
            'old_values' => json_encode($oldValues),
            'new_values' => json_encode($newValues),
        ]);

        return;
    }

    /**
     * Get Objective and key by Group Id
     * @param int $groupId
     */
    public function getObjective($groupId, $quarterId = null)
    {
        $typeOfGroup = Group::findOrFail($groupId)->type;
        if($typeOfGroup == Group::TYPE_USER) {
            $objectives = $this->isObjective()
                                    ->join('objective_user', 'objectives.id', '=', 'objective_user.objective_id')
                                    ->join('users', 'users.id', '=', 'objective_user.user_id')
                                    ->join('groups', 'groups.code', '=', 'users.code')
                                    ->where('objectives.group_id', $groupId)  
                                    ->where('objective_user.type', '=', OBJECTIVE::USER)
                                    ->select('objectives.*');
        } else {
            $objectives = $this->isObjective()->where('group_id', $groupId);
        }
        
        if (isset($quarterId)) {
            $objectives->where('quarter_id', $quarterId);
        }

        $objectives = $objectives->get();
        $arrObjective = [];
        foreach ($objectives as $objective) {
            $arrObjective[] = $this->getFullObjective($groupId, $objective->id);
        }

        return $arrObjective;
    }

    /**
     * Return all Objective in group
     * @return Objective
     */
    public function getObjectiveByGroup($groupId)
    {
        return $this->getObjective($groupId);
    }

    /**
     * Get Objective and key by Group Id and Quater Id
     * @return Objective
     */
    public function getObjectiveByQuarter($groupId, $quarterId)
    {
        return $this->getObjective($groupId, $quarterId);
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
        
        $objectiveOld = $this->where('id', $objectiveId)
            ->where('group_id', $groupId)
            ->firstOrFail();

        $objective = $this->where('id', $objectiveId)
            ->where('group_id', $groupId)
            ->firstOrFail();
                
        $message = translate('quarter.update_objective');

        $this->checkExpriedQuarter($objective->quarter_id, $message);
        $type = $this->checkTypeObjective($objective);
        
        $objective->update([
            'actual' => $data['actual'],
            'match' => Objective::UNMATCH,
        ]);

        $this->getLogObjective($groupId, Objective::UPDATE, $objective->objectiveable_type, $objectiveOld, $objective);
        
        $getFullObjectiveId = null;
        if ($objective->objectiveable_type == Objective::OBJECTIVE) {
            $getFullObjectiveId = $objective->id;
        } else {
            $getFullObjectiveId = isset($objective->parentObjective) ? $objective->parentObjective->id : $getFullObjectiveId;
        }
        
        $this->caculateObjectiveFromChild($groupId, $objective->id);
        
        return $this->getFullObjective($groupId, $getFullObjectiveId);
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
        $childs = $this->where('parent_id', $parentObjective->id)
               ->where(function ($q) {
                    $q->where('status', Objective::CANCEL)
                        ->orWhere('status', Objective::APPROVE);
               })
               ->get();
        
        $sum = $childs->sum(function ($objective) {
            return $objective->actual * $objective->weight;
        });

        $childObjectiveCount = $childs->count();
        if ($childObjectiveCount == 0) {
            $estimate = 0;
        } else {
            $estimate = (float)($sum / $childObjectiveCount);
        }

        $parentObjective->update([
            'estimate' => $estimate,
        ]);

        $match = $parentObjective->match;
        if ($match == OBJECTIVE::MATCH) {
            $parentObjective->update([
                'actual' => $estimate,
            ]);
        }

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
        $objectiveOld = $this->isObjective()
            ->where('id', $data['objectiveId'])
            ->firstOrFail();

        $objective = $this->isObjective()
            ->where('id', $data['objectiveId'])
            ->firstOrFail();

        $this->checkUserIsGroupManager($objective->group_id);

        $message = translate('quarter.link_objective');
        $this->checkExpriedQuarter($objective->quarter_id, $message);

        $parentObj = $this->isKeyResult()
            ->where('id', $data['keyResultId'])
            ->firstOrFail();
    
        $objective->update([
            'parent_id' => $parentObj->id,
            'status' => Objective::WAITING,
        ]);

        $this->getLogObjective($groupId, Objective::LINK, $objective->objectiveable_type, $objectiveOld, $objective);
        
        return $objective;
    }

    /**
     * Check verify common
     *
     * @param int $groupId
     * @param int $objectiveId
     * @throws UnknownException
     */
    public function verifyLinkObj($groupId, $objectiveId)
    {
        $this->checkUserIsGroupManager($groupId);

        $objective = $this->findOrFail($objectiveId);

        $objective->update([
            'status' => Objective::APPROVE,
        ]);

        $this->caculateObjectiveFromChild($groupId, $objectiveId);
    }

    /**
     * Manger verify objective linked
     *
     * @param int $groupId
     * @param int t$objectiveId
     * @return Objective
     * @throws UnknownException
     */
    public function verifyLink($groupId, $objectiveId)
    {
        $this->verifyLinkObj($groupId, $objectiveId);

        return $this->findOrFail($objectiveId);
    }

    /**
     * Verify all objectives link to keyresult
     *
     * @param int $groupId
     * @param int $keyResultId
     * @throws UnknownException
     */
    public function verifyAllLink($groupId, $keyResultId)
    {
        $keyResult = $this->findOrFail($keyResultId);

        $objectivejLink = $keyResult->childObjective;

        foreach ($objectivejLink as $objective) {
            $this->verifyLinkObj($groupId, $objective->id);
        }

        return $keyResult;
    }

    /**
     * Remove link common
     *
     * @param int $groupId
     * @param int $objectiveId
     * @return mixed
     * @throws UnknownException
     */
    public function removeLinkObj($groupId, $objectiveId)
    {
        $this->checkUserIsGroupManager($groupId);

        $objective = $this->findOrFail($objectiveId);

        if ($objective->status == Objective::APPROVE) {
            return $objective;
        }

        $objective->update([
            'status' => Objective::CANCEL,
            'parent_id' => null,
        ]);
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
        $this->removeLinkObj($groupId, $objectiveId);

        return $this->findOrFail($objectiveId);
    }

    public function removeLinkObjectiveAccepted($groupId, $objectiveId)
    {
        $this->checkUserIsGroupManager($groupId);

        $objective = $this->findOrFail($objectiveId);

        if ($objective->status == Objective::APPROVE) {
            $objective->update([
                'status' => Objective::CANCEL,
                'parent_id' => null,
            ]);
        }

        return $objective;
    }

    /**
     * Remove all objectives link to keyresult
     *
     * @param int $groupId
     * @param int $keyResultId
     * @throws UnknownException
     */
    public function removeAllLink($groupId, $keyResultId)
    {
        $keyResult = $this->findOrFail($keyResultId);

        $objectivejLink = $keyResult->childObjective;

        foreach ($objectivejLink as $objective) {
            $this->removeLinkObj($groupId, $objective->id);
        }

        return $this->findOrFail($keyResultId);
    }

    /**
     * Update name objective
     *
     * @param int $groupId
     * @param int $objectiveId
     * @param int $data
     * @return Objective
     */
    public function updateName($groupId, $objectiveId, $data)
    {
        return $this->updateContent($groupId, $objectiveId, $data);
    }

    /**
     * Update weight objective
     *
     * @param int $groupId
     * @param int $objectiveId
     * @param int $data
     * @return Objective
     */
    public function updateWeight($groupId, $objectiveId, $data)
    {
        return $this->updateContent($groupId, $objectiveId, $data);
    }

    /**
     * Update content objective
     *
     * @param int $objectiveId
     * @param int $data
     * @return Objective
     */
    public function updateContent($groupId, $objectiveId, $data)
    {
        $this->checkUserIsGroupManager($groupId);

        $objectiveOld = $objective = $this->where('group_id', $groupId)
                    ->findOrFail($objectiveId);

        $objective = $this->where('group_id', $groupId)
            ->findOrFail($objectiveId);
            
        if ($objective->objectiveable_type == Objective::OBJECTIVE) {
            $message = translate('quarter.update_objective');
        } else {
            $message = translate('quarter.update_key');
        }
        
        $this->checkExpriedQuarter($objective->quarter_id, $message);

        $keyData = key($data);
        $valueData = $data[$keyData];
        
        $objective->update([
            $keyData => $valueData,
        ]);

        $this->getLogObjective($groupId, Objective::UPDATE, $objective->objectiveable_type, $objectiveOld, $objective);
        
        return $objective;
    }

    public function checkTypeObjective($objective)
    {
        if ($objective->objectiveable_type == Objective::OBJECTIVE) {
            $type = Objective::OBJECTIVE;
        } else {
            $type = Objective::KEYRESULT;
        }

        return $type;
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
            
        $message = translate('quarter.match');
        $this->checkExpriedQuarter($objective->quarter_id, $message);

        $objective->update([
            'actual' => $objective->estimate,
            'match' => Objective::MATCH,
        ]);

        return $this->caculateObjectiveFromChild($groupId, $objective->id);
    }

    /**
     * Show detail Objective
     * @param int $groupId
     * @param int $objectiveId
     * @return void
     */
    public function showObjectiveDetail($groupId, $objectiveId)
    {
        return $this->getFullObjective($groupId, $objectiveId);
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

        $objectiveDeleted = $this->where('id', $objectiveId)
            ->where('group_id', $groupId)
            ->firstOrFail();

        $objective = $this->where('id', $objectiveId)
            ->where('group_id', $groupId)
            ->firstOrFail();


        if ($this->checkParentObjective($objectiveId)) {
            $childObject = $objective->childObjective;
            foreach ($childObject as $child) {
                $childObjectLink = $child->childObjective;
                foreach ($childObjectLink as $childLink) {
                    if ($childLink->status == Objective::CANCEL) {
                        $childLink->delete();
                    } else {
                        $childLink->update([
                            'parent_id' => null,
                            'status' => Objective::CANCEL,
                        ]);
                    }
                }

                if ($child->status == Objective::CANCEL) {
                    $child->delete();
                } else {
                    $child->update([
                        'parent_id' => null,
                        'status' => Objective::CANCEL,
                    ]);
                }
            }
        }

        $type = $this->checkTypeObjective($objective);

        $this->getLogObjective($groupId, Objective::DELETE, $objectiveDeleted->objectiveable_type, $objectiveDeleted, []);

        $objective->delete();


        if ($objective->objectiveable_type != Objective::OBJECTIVE || $objective->status == Objective::APPROVE) {
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
