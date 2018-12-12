<?php

namespace App\Http\Controllers\Cms\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Contracts\Services\PassportInterface;
use App\Contracts\Repositories\UserRepository;
use App\Http\Controllers\Api\ApiController;
use App\Exceptions\Api\NotFoundException;
use App\Eloquent\User;
use App\Eloquent\Group;
use Carbon\Carbon;
use Fauth;
use Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';
    protected $repository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepository $repository)
    {
        $this->middleware('guest')->except('logout');
        $this->repository = $repository;
        
    }

    /**
    * Redirect the user to the Auth-Framgia authentication page.
    *
    * @return Response
    */
    public function redirectToProvider()
    {
        return Fauth::driver(config('settings.default_provider'))->redirect();
    }

    /**
     * Obtain the user information from Auth-Framgia.
     *
     * @return Response
     */
    public function handleProviderCallback()
    {
        $userFromAuthServer = Fauth::driver(config('settings.default_provider'))->user();
        $spaces = [];
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

        $listGroup[] = $this->createGroupWithLoginUser($userFromAuthServer);

        $currentUser = $this->repository->updateOrCreate(
            [
                'email' => $userFromAuthServer['email'],
            ],
            [
                'name' => $userFromAuthServer['name'],
                'code' => $userFromAuthServer['employee_code'],
                'birthday' => Carbon::parse($userFromAuthServer['birthday'])->toDateString(),
                'gender' => array_get(config('model.user.gender'), $userFromAuthServer['gender']),
                'mission' => $userFromAuthServer['position']['name'],
                'avatar' => $userFromAuthServer['avatar'],
                'token_verification' => str_random(60),
                'status' => array_get(config('model.user.status'), $userFromAuthServer['status']),
            ]
        );

        //Sync Group With User And Set Role to Loggin User's Group
        $currentUser->groups()->syncWithoutDetaching($listGroup);
        $currentUser->groups()->updateExistingPivot(last($listGroup), ['manager' => true]);
        $currentUser->workspaces()->syncWithoutDetaching($spaces);

        if ($currentUser) {
            if (Auth::loginUsingId($currentUser->id)) {
                return redirect()->route('dashboard');
            }
        }
    }

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
