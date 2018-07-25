<?php

namespace App\Services;

use App\Contracts\Services\SocialInterface;
use Fauth;
use Carbon\Carbon;
use App\Contracts\Repositories\UserRepository;
use App\Eloquent\User;
use App\Exceptions\Api\NotFoundException;
use App\Eloquent\Group;

class Social implements SocialInterface
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    
    /**
     * Get User Info From WSM
     * @param email
     * @param password
     * @return App\Eloquent\User
     **/
    public function getUserFromWsm($email, $password)
    {
        $accessToken = $this->getAccessTokenFromWsm($email, $password);
        $user = $this->getUserInfo($accessToken);

        return $user;
    }

    /**
     * Get Access Token From WSM
     * @param string $email
     * @param string $password
     * @return string $accessToken
     **/
    public function getAccessTokenFromWsm($email, $password)
    {
        $response = Fauth::driver(config('settings.default_provider'))->getTokenByPasswordGrant($email, $password);
        if (isset($response['error'])) {
            throw new NotFoundException($response['error'], 404);
        }

        return $response['access_token'];
    }

    /**
     * Update Or Create And Get User Info
     * @param string $accessToken
     * @return App\Eloquent\User
     **/
    public function getUserInfo($accessToken)
    {
        $userFromAuthServer = Fauth::driver(config('settings.default_provider'))->getUserByToken($accessToken);
        //Get Group ID
        $userGroupId = $this->getGroupDetail($userFromAuthServer);
        //Get Workspace from Auth Sever
        $workspaceInfo = $userFromAuthServer['workspaces'][0] ? : null;
        $birthday = Carbon::parse($userFromAuthServer['birthday'])->toDateString();
        $currentUser = $this->userRepository->model()->updateOrCreate([
            'email' => $userFromAuthServer['email'],
            'name' => $userFromAuthServer['name'],
            'code' => $userFromAuthServer['employee_code'],
            'birthday' => $birthday,
            'location' => $workspaceInfo['name'],
            'avatar' => $userFromAuthServer['avatar'],
            'mission' => $userFromAuthServer['position']['name'],
            'gender' => array_get(config('model.user.gender'), $userFromAuthServer['gender']),
            'status' => array_get(config('model.user.status'), $userFromAuthServer['status']),
        ]);
        //Check if data exist in pivot table
        $exists = $currentUser->groups->contains($userGroupId);
        if (!$exists) {
            $currentUser->groups()->attach($userGroupId);
        }

        return $currentUser->with('groups')->get();
    }

    /**
     * Update Or Create And Get Group Info
     * @param string $accessToken
     * @return int $group_id
     **/
    public function getGroupDetail($userFromAuthServer)
    {
        //Get Group Detail From Auth Sever
        $groupInfo = $userFromAuthServer['groups'][0] ? : null;
        //Create Group From Detail
        $parentGroupInfo = $groupInfo['parent_path'];
        $parentGroupCode = null;
        //Parent Group Of Current User Group
        foreach ($parentGroupInfo as $group) {
            $group = app(Group::class)::updateOrCreate([
                'code' => $group['id'],
                'name' => $group['name'],
                'parent_id' => $parentGroupCode,
            ]);
            $parentGroupCode = $group->code;
        }
        //Current User Group
        $groupUser = app(Group::class)::updateOrCreate([
            'code' => $groupInfo['id'],
            'name' => $groupInfo['name'],
            'parent_id' => $parentGroupCode,
        ]);

        return $groupUser->id;
    }
}
