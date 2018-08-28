<?php

namespace App\Repositories;

use App\Contracts\Repositories\CommentRepository;
use Auth;
use App\Eloquent\Comment;
use App\Exceptions\Api\NotFoundException;

class CommentRepositoryEloquent extends AbstractRepositoryEloquent implements CommentRepository
{
    public function model()
    {
        return app(Comment::class);
    }

    /**
     * @param $objectiveId
     * @param $data
     * @return mixed
     */
    public function commentObjective($objectiveId, $data)
    {
        $userId = auth::guard('fauth')->user()->id;
        $comment = $this->model()->create([
            'user_id' => $userId,
            'content' => $data['content'],
            'objective_id' => $objectiveId
        ]);

        return $comment;
    }
}

