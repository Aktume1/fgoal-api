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
use App\Eloquent\ObjectiveLink;
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

    public function getAllLink($groupId, $objectiveId) {
        $objectiveLinks = ObjectiveLink::where('objective_id', $objectiveId)->get();

        $linkTo = null;
        $getLinkTo = null;

        foreach ($objectiveLinks as $objLink) {
            $keyResult = $this->where('id', $objLink->key_result_id)->first();

            $keyResult->setAttribute('group', $keyResult->group);
            $linkTo['status'] = $objLink->status;
            $linkTo['key_result'] = $keyResult;
            $getLinkTo[] = $linkTo;
        }

        return $getLinkTo;
    }
    
    public function getFullObjective($groupId, $objectiveId)
    {
        $objective = $this->where('id', $objectiveId)
                ->where('group_id', $groupId)
                ->firstOrFail();

        $objective->makeHidden('group_id');
        $objective->setAttribute('group', $objective->group);
        $linkTo = null;
        $getLinkTo = null;

        foreach ($objective->childObjective as $childs) {
            $childs->makeHidden('group_id');
            $childs->setAttribute('group', $childs->group);

            $ObjectiveLink = ObjectiveLink::where('key_result_id', $childs->id)->first();

            if ($ObjectiveLink) {
                $ObjectiveLinkId = $ObjectiveLink->objective_id;
                $ObjectiveLinkStatus = $ObjectiveLink->status;

                $objectiveLinkTo = $this->where('id', $ObjectiveLinkId)->first();
                $objectiveLinkTo->setAttribute('group', $objectiveLinkTo->group);

                $linkTo['status'] = $ObjectiveLinkStatus;
                $linkTo['key_result'] = $objectiveLinkTo;
                $getLinkTo[] = $linkTo;

                $childs->setAttribute('link_to', $getLinkTo);
                
                $objectiveLinkTo->setAttribute('link_to', $this->getAllLink($objectiveLinkTo->group_id, $ObjectiveLinkId));
            }   
        }

        $objective->setAttribute('link_to', $this->getAllLink($groupId, $objectiveId));

        return $objective;
    }

    public function getFullKeyResult($groupId, $keyResultId)
    {
        $keyResult = $this->where('id', $keyResultId)
                ->where('group_id', $groupId)
                ->firstOrFail();

        $keyResult->makeHidden('group_id');
        $keyResult->setAttribute('group', $keyResult->group);

        $linkTo = null;
        $getLinkTo = null;
        $objectiveLinkTo = ObjectiveLink::where('key_result_id', $keyResult->id)->get();

        foreach($objectiveLinkTo as $objLinkTo) {
            $objLinkTo->makeHidden('group_id');
            $objLinkTo->setAttribute('group', $objLinkTo->group);

            $objective = $this->where('id', $objLinkTo->objective_id)->first();

            $objective->setAttribute('group', $objective->group);

            $linkTo['status'] = $objLinkTo->status;
            $linkTo['objective'] = $objective;
            $getLinkTo[] = $linkTo;
        }

        $keyResult->setAttribute('link_to', $getLinkTo);

        return $keyResult;
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
            'target' => $data['target'],
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

        $sumWeight = $childs->sum(function ($objective) {
            return $objective->weight;
        });

        $childObjectiveCount = $childs->count();
        if ($childObjectiveCount == 0) {
            $estimate = 0;
        } else {
            $estimate = (float)($sum / $sumWeight);
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
            ->where('id', $data['objective_id'])
            ->firstOrFail();

        $objective = $this->isObjective()
            ->where('id', $data['objective_id'])
            ->firstOrFail();

        $this->checkUserIsGroupManager($objective->group_id);

        $message = translate('quarter.link_objective');

        $this->checkExpriedQuarter($objective->quarter_id, $message);

        $keyResultIds = $data['key_result_id'];
        $getKeyResults = explode(',', $keyResultIds);

        foreach ($getKeyResults as $keyResultId) {
            $parentObj = $this->isKeyResult()
            ->where('id', $keyResultId)
            ->firstOrFail();

            $objective_link = ObjectiveLink::create([
                'objective_id' => $objective->id,
                'key_result_id' => $parentObj->id,
                'status' => ObjectiveLink::WAITING,
            ]);

            $objectiveLinkTo = $this->where('id', $keyResultId)->first();
            $objectiveLinkTo->setAttribute('status', $objective_link->status);
            $arrayObjectiveLinkTo[] = $objectiveLinkTo;
            
            $this->getLogObjective($groupId, Objective::LINK, $objective->objectiveable_type, $objective, $parentObj);
        }

        return $arrayObjectiveLinkTo;
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

        $keyResult = ObjectiveLink::where('key_result_id', $objectiveId)->first();
        $keyResult->update([
            'status' => ObjectiveLink::APPROVE,
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

        $objective = $this->findOrFail($objectiveId);
        $keyResult = ObjectiveLink::where('key_result_id', $objectiveId)->first();
        $objective->setAttribute('status', $keyResult->status);

        return $objective;
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
        $keyResults = ObjectiveLink::where('key_result_id', $keyResultId)->get();

        foreach ($keyResults as $keyResult) {
            $keyResult->update([
                'status' => ObjectiveLink::APPROVE,
            ]);
            $objective = $this->where('id', $keyResult->objective_id)->first();
            $objective->setAttribute('status', $keyResult->status);
            $arrObjective[] = $objective;
        }

        return $arrObjective;
    }

    /**
     * Remove link common
     *
     * @param int $groupId
     * @param int $objectiveId
     * @return mixed
     * @throws UnknownException
     */
    public function removeLinkObj($groupId, $objectiveId, $keyResultId)
    {
        $this->checkUserIsGroupManager($groupId);

        $objective = ObjectiveLink::where('objective_id', $objectiveId)->where('key_result_id', $keyResultId)->first();

        if ($objective->status == ObjectiveLink::APPROVE) {
            return $objective;
        }
        
        $objective->update([
            'status' => ObjectiveLink::CANCEL,
        ]);
        $objective->delete();
    }

    /**
     * Update link objective to null
     *
     * @param int $groupId
     * @param int $objectiveId
     * @return Objective
     * @throws UnknownException
     */
    public function removeLinkedObjective($groupId, $objectiveId, $data)
    {

        $this->removeLinkObj($groupId, $objectiveId, $data['key_result_id']);

        $keyResult = ObjectiveLink::withTrashed()->where('key_result_id', $data['key_result_id'])->first();
        $objective = $this->where('id', $keyResult->objective_id)->first();

        $objective->setAttribute('status', $keyResult->status);
        $objective->setAttribute('link_to', $this->getAllLink($groupId, $objectiveId));
        return $objective;
    }

    public function removeLinkObjectiveAccepted($groupId, $objectiveId, $data)
    {
        $this->checkUserIsGroupManager($groupId);

        $objectiveLink = ObjectiveLink::where('objective_id', $objectiveId)->where('key_result_id', $data['key_result_id'])->first();
       
        if ($objectiveLink->status == ObjectiveLink::APPROVE) {
            $objectiveLink->update([
                'status' => ObjectiveLink::CANCEL,
            ]);
        }

        $objective = $this->where('id', $objectiveLink->objective_id)->first();
        $objective->setAttribute('status', $objectiveLink->status);
        $objective->setAttribute('link_to', $this->getAllLink($groupId, $objectiveId));

        $objectiveLink->delete();

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
        $keyResults = ObjectiveLink::where('key_result_id', $keyResultId)->get();

        foreach ($keyResults as $keyResult) {
            $keyResult->update([
                'status' => ObjectiveLink::CANCEL,
            ]);
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
     * Update target vs unit objective
     *
     * @param int $groupId
     * @param int $objectiveId
     * @param int $data
     * @return Objective
     */
    public function updateTargetVsUnit($groupId, $objectiveId, $data)
    {
        $objective = $this->where('group_id', $groupId)
                    ->findOrFail($objectiveId);

        $objectiveType = $objective->objectiveable_type;

        if ($objectiveType == Objective::KEYRESULT) {
            $this->updateContent($groupId, $objectiveId, $data['unit_id']);

            return $this->updateContent($groupId, $objectiveId, $data['target']);
        }

        return $objective;
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
     * Show detail Key Result
     * @param int $groupId
     * @param int $objectiveId
     * @return void
     */
    public function showKeyResultDetail($groupId, $keyResultId)
    {
        return $this->getFullKeyResult($groupId, $keyResultId);
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
