<?php

namespace App\Contracts\Repositories;

interface CommentRepository extends AbstractRepository
{
    public function commentObjective($objectiveId, $data);

    public function getCommentObjective($objectiveId);
}
