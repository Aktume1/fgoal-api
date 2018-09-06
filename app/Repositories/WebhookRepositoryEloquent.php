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

    /**
     * Hanle webhook WSM from data get by controlelr
     * @return void
     */
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

    /**
     * Update or create user from Webhook
     * @return User
     */
    public function updateOrCreateUserWSM($dataWebhook)
    {
        $hookUser = $dataWebhook['user'];
        $listWorkspace = $hookUser['workspaces'];
        $listGroup = $this->getGroupDetail($dataWebhook);
        
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

        $currentUser->groups()->syncWithoutDetaching($listGroup);
        $currentUser->groups()->updateExistingPivot(last($listGroup), ['manager' => true]);
        $currentUser->workspaces()->syncWithoutDetaching($listWorkspace);

        return User::findOrFail($currentUser->id);
    }

    /**
     * Get group detail
     * @return listgroup
     */
    public function getGroupDetail($dataWebhook)
    {
        $listGroup = [];
        //Get Group Detail From Auth Sever
        $listGroupInfo = $dataWebhook['user']['groups'];
        foreach ($listGroupInfo as $groupInfo) {
            //Create Group From Detail
            $parentGroupInfo = $groupInfo['parent_path'];
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
                    'code' => $groupInfo['id'],
                ],
                [
                    'name' => $groupInfo['name'],
                    'parent_id' => $parentGroupCode,
                    'type' => Group::DEFAULT_GROUP,
                ]
            );

            $listGroup[] = $groupUser->id;
        }

        $listGroup[] = $this->createGroupWithLoginUser($dataWebhook);

        return $listGroup;
    }

    /**
     * Create group for login user
     *  return GroupId
     */
    public function createGroupWithLoginUser($dataWebhook)
    {
        $groupUser = app(Group::class)::updateOrCreate(
            [
                'code' => $dataWebhook['user']['employee_code'],
            ],
            [
                'name' => $dataWebhook['user']['name'],
            ]
        );

        return $groupUser->id;
    }

    /**
     * Delete user from webhook
     * @return User
     */
    public function deleteUserWSM($dataWebhook)
    {
        $userEmail = $dataWebhook['user']['email'];
        $groupUser = $dataWebhook['user']['name'];

        $user = User::where('email', $userEmail)->first();
        $group = Group::where('name', $groupUser);

        $group->delete();
        $user->groups()->detach();
        $user->workspaces()->detach();
        $user->forceDelete();

        return $user;
    }

    /**
     * Update or create group from Webhook
     * @return group
     */
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
    
    /**
     * delete group from Webhook
     * @return Group
     */
    public function deleteGroupWSM($dataWebhook)
    {
        $hookGroup = $dataWebhook['group'];
        $group = Group::findOrFail($hookGroup['id']);
        $group->users()->detach();
        $group->delete();

        return $group;
    }

    /**
     * Assign User to Group from Webhook
     * @return User
     */
    public function assignUserToGroup($dataWebhook)
    {
        $hookUser = $dataWebhook['user_group']['user'];
        $hookGroup = $dataWebhook['user_group']['group'];

        $user = User::findOrFail($hookUser['id']);
        $user->groups()->syncWithoutDetaching($hookGroup['id']);

        return $user;
    }

    /**
     * Unassign User to Group from Webhook
     * @return User
     */
    public function unassignUserToGroup($dataWebhook)
    {
        $hookUser = $dataWebhook['user_group']['user'];
        $hookGroup = $dataWebhook['user_group']['group'];

        $user = User::findOrFail($hookUser['id']);
        $user->groups()->detach($hookGroup['id']);

        return $user;
    }
}
