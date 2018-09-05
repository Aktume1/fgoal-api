<?php

namespace App\Repositories;

use App\Contracts\Repositories\WebhookRepository;
use App\Eloquent\User;
use App\Eloquent\Group;

class WebhookRepositoryEloquent extends AbstractRepositoryEloquent implements WebhookRepository
{
    public function model()
    {
        return app(User::class);
    }

    public function handleWebhookWSM($dataWebhook)
    {
        if (isset($dataWebhook['user'])) {
            switch ($dataWebhook['action']) {
                case 'created':
                    return $this->updateOrCreateUserWSM($dataWebhook);

                case 'updated':
                    return $this->updateOrCreateUserWSM($dataWebhook);

                case 'deleted':
                    return $this->deleteUserWSM($dataWebhook);
            }
        } elseif (isset($dataWebhook['group'])) {
            switch ($dataWebhook['action']) {
                case 'created':
                    return $this->updateOrCreateGroupWSM($dataWebhook);

                case 'updated':
                    return $this->updateOrCreateGroupWSM($dataWebhook);

                case 'deleted':
                    return $this->deleteGroupWSM($dataWebhook);
            }
        } elseif (isset($dataWebhook['user_group'])) {
            switch ($dataWebhook['action']) {
                case 'created':
                    return $this->assignUserToGroup($dataWebhook);

                case 'updated':
                    return $this->assignUserToGroup($dataWebhook);

                case 'deleted':
                    return $this->unassignUserToGroup($dataWebhook);
            }
        }
    }

    public function updateOrCreateUserWSM($dataWebhook)
    {
        $hookUser = $dataWebhook['user'];
        $listWorkspace = $hookUser['workspaces'];
        
        $currentUser = $this->updateOrCreate(
            [
                'email' => $hookUser['email'],
            ],
            [
                'name' => $hookUser['name'],
                'code' => $hookUser['employee_code'],
                'birthday' => $hookUser['birthday'],
                'mission' => $hookUser['position'],
                'location' => $hookUser['name'],
                'avatar' => $hookUser['avatar'],
                'gender' => array_get(config('model.user.gender'), $hookUser['gender']),
                'status' => array_get(config('model.user.status'), $hookUser['status']),
            ]
        );

        $currentUser->workspaces()->syncWithoutDetaching($listWorkspace);

        return $this->findOrFail($currentUser->id);
    }

    public function deleteUserWSM($dataWebhook)
    {
        $userId = $dataWebhook['user']['id'];
        $user = $this->findOrFail($userId);
        $user->groups()->detach();
        $user->workspaces()->detach();

        $user->forceDelete();

        return $user;
    }

    public function updateOrCreateGroupWSM($dataWebhook)
    {
        $hookGroup = $dataWebhook['group'];

        //Create Group From Detail
        $parentGroupInfo = $hookGroup['parent_path'];
        $parentGroupCode = null;

        //Parent Group Of Current User Group
        foreach ($parentGroupInfo as $group) {
            $group = app(Group::class)::updateOrCreate(
                [
                    'code' => $group['id'],
                ],
                [
                    'name' => $group['name'],
                    'parent_id' => $parentGroupCode,
                    'type' => Group::DEFAULT_GROUP,
                ]
            );
            $parentGroupCode = $group->code;
        }
        //Current User Group
        $groupUser = app(Group::class)::updateOrCreate(
            [
                'code' => $hookGroup['id'],
            ],
            [
                'name' => $hookGroup['name'],
                'parent_id' => $parentGroupCode,
                'type' => Group::DEFAULT_GROUP,
            ]
        );

        return $groupUser;
    }
    
    public function deleteGroupWSM($dataWebhook)
    {
        $hookGroup = $dataWebhook['group'];
        $group = Group::findOrFail($hookGroup['id']);
        $group->users()->detach();
        $group->delete();

        return $group;
    }

    public function assignUserToGroup($dataWebhook)
    {
        $hookUser = $dataWebhook['user_group']['user'];
        $hookGroup = $dataWebhook['user_group']['group'];

        $user = User::findOrFail($hookUser['id']);
        $user->groups()->syncWithoutDetaching($hookGroup['id']);

        return $user;
    }

    public function unassignUserToGroup($dataWebhook)
    {
        $hookUser = $dataWebhook['user_group']['user'];
        $hookGroup = $dataWebhook['user_group']['group'];

        $user = User::findOrFail($hookUser['id']);
        $user->groups()->detach($hookGroup['id']);

        return $user;
    }
}
