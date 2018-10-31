<?php

namespace App\Repositories;

use App\Contracts\Repositories\CommentRepository;
use App\Eloquent\Objective;
use Auth;
use App\Eloquent\Comment;
use App\Exceptions\Api\NotFoundException;
use App\Exceptions\Api\UnknownException;

class CommentRepositoryEloquent extends AbstractRepositoryEloquent implements CommentRepository
{
    public function model()
    {
        return app(Comment::class);
    }

    public function checkUser($groupId, $comment)
    {
        $this->setGuard('fauth');
        if ( (!$this->user->isGroupManager($groupId)) && ($this->user->id != $comment->user_id) ) {
            throw new UnknownException(translate('http_message.unauthorized'));
        }
    }

    /**
     * @param $objectiveId
     * @param $data
     * @return mixed
     */
    public function commentObjective($objectiveId, $data)
    {
        $userId = Auth::guard('fauth')->user()->id;
        $comment = $this->create([
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

    public function updateComment($objectiveId, $commentId, $data)
    {
        $comment = Comment::findOrFail($commentId);
        $groupId = Objective::findOrFail($objectiveId)->group_id;
        
        $this->checkUser($groupId, $comment);

        $comment->update([
            'content' => $data['content'],
        ]);

        $name = $comment->user->name;
        $avatar = $comment->user->avatar;
        $comment->setAttribute('user_name', $name);
        $comment->setAttribute('user_avatar', $avatar);
        $comment->makeHidden('user');

        return $comment;
    }

    public function deleteComment($objectiveId, $commentId)
    {   
        $comment = Comment::findOrFail($commentId);
        $groupId = Objective::findOrFail($objectiveId)->group_id;
        $this->checkUser($groupId, $comment);

        $comment->delete();

        return;
    }
    
}

