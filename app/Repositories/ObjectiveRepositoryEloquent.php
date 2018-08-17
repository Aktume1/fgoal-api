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

    /**
     * Create Objective
     * @param int $groupId
     * @param array $data
     * @return Objective
     */
    public function create($groupId, $data)
    {
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
     * Get Objective
     * @return Objective
     */
    public function isObjective()
    {
        return $this->where('objectiveable_type', 'Objective');
    }
    /**
     * Get Objective and key by Group Id
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
}
