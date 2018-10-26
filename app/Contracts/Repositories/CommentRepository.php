<?php

namespace App\Contracts\Repositories;

interface CommentRepository extends AbstractRepository
{
    public function commentObjective($objectiveId, $data);

    public function getCommentObjective($objectiveId);

    public function updateComment($objectiveId, $commentId, $data);

    public function deleteComment($objectiveId, $commentId);

    public function checkUser($groupId, $comment);
}
