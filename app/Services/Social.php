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
     * Get User Info From WSM By Password
     * @param email
     * @param password
     * @return App\Eloquent\User
     **/
    public function getUserByPasswordGrant($email, $password)
    {
        $token = $this->getTokenByPasswordGrant($email, $password);
        $user = $this->getUserInfo($token['access_token']);
        $user['refresh_token'] = $token['refresh_token'];

        return $user;
    }

    /**
     * Get Token From WSM
     * @param string $email
     * @param string $password
     * @return array token
     **/
    public function getTokenByPasswordGrant($email, $password)
    {
        $response = Fauth::driver(config('settings.default_provider'))->getTokenByPasswordGrant($email, $password);
        if (isset($response['error'])) {
            throw new NotFoundException($response['error'], 404);
        }

        return $response;
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
        $listGroup = $this->getGroupDetail($userFromAuthServer);
        //Get Workspace from Auth Sever
        $workspaceInfo = $userFromAuthServer['workspaces'][0] ? : null;
        $birthday = Carbon::parse($userFromAuthServer['birthday'])->toDateString();
        $currentUser = $this->userRepository->updateOrCreate(
            [
                'email' => $userFromAuthServer['email'],
            ],
            [
                'name' => $userFromAuthServer['name'],
                'code' => $userFromAuthServer['employee_code'],
                'birthday' => $birthday,
                'location' => $workspaceInfo['name'],
                'avatar' => $userFromAuthServer['avatar'],
                'mission' => $userFromAuthServer['position']['name'],
                'gender' => array_get(config('model.user.gender'), $userFromAuthServer['gender']),
                'status' => array_get(config('model.user.status'), $userFromAuthServer['status']),
                'token_verification' => str_random(60),
            ]
        );
        //Check if data exist in pivot table
  
        $exists = $currentUser->groups()->sync($listGroup);
          
     
        //Return user from database
        $user = $this->userRepository->where('id', $currentUser->id)->with('groups')->first();

        return $user;
    }

    /**
     * Update Or Create And Get Group Info
     * @param string $accessToken
     * @return array $listGroup
     **/
    public function getGroupDetail($userFromAuthServer)
    {
        $listGroup = [];
        //Get Group Detail From Auth Sever
        $listGroupInfo = $userFromAuthServer['groups'];
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
                ]
            );
            $listGroup[] = $groupUser->id;
        }
        $listGroup[] = $this->createGroupWithLoginUser($userFromAuthServer);

        return $listGroup;
    }

    /**
     * Get Token From WSM By Refresh Token
     * @param string $refreshToken
     * @return array token
     **/
    public function refreshToken($refreshToken)
    {
        $response = Fauth::driver(config('settings.default_provider'))->refreshToken($refreshToken);
        if (isset($response['error'])) {
            throw new NotFoundException($response['error'], 404);
        }
        
        return $response;
    }

    /**
     * Get User Info From WSM By Refresh Token
     * @param email
     * @param password
     * @return App\Eloquent\User
     **/
    public function getUserByRefreshToken($refreshToken)
    {
        $token = $this->refreshToken($refreshToken);
        $user = $this->getUserInfo($token['access_token']);
        $user['refresh_token'] = $token['refresh_token'];

        return $user;
    }

    /**
     * Create group for loginnd user
     *  return group id
     */
    public function createGroupWithLoginUser($userFromAuthServer)
    {
        $groupUser = app(Group::class)::updateOrCreate(
            [
                'code' => $userFromAuthServer['employee_code'],
            ],
            [
                'name' => $userFromAuthServer['name'],
            ]
        );
        
        return $groupUser->id;
    }
}
