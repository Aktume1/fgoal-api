<?php

namespace App\Repositories;

use App\Contracts\Repositories\CommentRepository;
use App\Eloquent\Objective;
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
        $userId = Auth::guard('fauth')->user()->id;
        $comment = $this->model()->create([
            'user_id' => $userId,
            'content' => $data['content'],
            'objective_id' => $objectiveId
        ]);

        return $comment;
    }

    public function getCommentObjective($objectiveId)
    {
        $objective = Objective::findOrFail($objectiveId);

        $comments = $objective->comments;
        foreach ($comments as $row) {
            $name = $row->user->name;
            $avatar = $row->user->avatar;

            $row->setAttribute('user_name', $name);

            $row->setAttribute('user_avatar', $avatar);

            $row->makeHidden('user');
        }

        return $comments;
    }
}

