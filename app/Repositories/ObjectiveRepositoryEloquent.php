<?php

namespace App\Repositories;

use App\Eloquent\Objective;
use App\Contracts\Repositories\ObjectiveRepository;

class ObjectiveRepositoryEloquent extends AbstractRepositoryEloquent implements ObjectiveRepository
{
    public function model()
    {
        return app(Objective::class);
    }

    public function create($groupId, $data)
    {
        if (!isset($data['parent_id'])) {
            $data['parent_id'] = null;
        }
        $objective = $this->model()->create([
            'name' => $data['name'],
            'group_id' => $groupId,
            'unit_id' => $data['unit_id'],
            'quarter_id' => $data['quarter_id'],
            'parent_id' => $data['parent_id'],
        ]);

        return $this->model()->find($objective->id);
    }

    /**
     * Get Objective
     * @return Objective
     */
    public function isObjective()
    {
        return $this->model()->where('parent_id', null);
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
