<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Contracts\Services\PassportInterface;
use App\Contracts\Services\SocialInterface;
use App\Http\Controllers\Api\ApiController;
use App\Exceptions\Api\NotFoundException;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RefreshTokenRequest;
use Carbon\Carbon;
use App\Contracts\Repositories\UserRepository;
use Illuminate\Http\Request;

class LoginController extends ApiController
{
    use AuthenticatesUsers;

    protected $repository;

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

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }

    public function login(
        LoginRequest $request,
        PassportInterface $service
    ) {
        $data = $request->only(['email', 'password']);
        $isActiveAccount = $this->repository->checkActiveAccount($data['email']);

        if ($this->attemptLogin($request)) {
            if (! $isActiveAccount) {
                throw new NotFoundException(__('auth.not_active'), NOT_FOUND);
            }

            $response = $service->passwordGrantToken($data);

            if (isset($response->error)) {
                throw new NotFoundException($response->message, NOT_FOUND);
            }

            if (isset($response->access_token)) {
                $currentUser = $this->repository->getUserByEmail($data['email']);
                $response->user = $currentUser;

                $this->compacts['data'] = $response;
            }
        } else {
            throw new NotFoundException(__('auth.failed'), UNAUTHORIZED);
        }

        return $this->jsonRender();
    }

    public function loginWithWsm(LoginRequest $request, SocialInterface $service)
    {
        $data = $request->only(['email', 'password', 'firebase_token']);

        $this->compacts['data'] = $service->getUserByPasswordGrant($data['email'], $data['password']);
        $this->compacts['description'] = 'Signed in successfully.';

        if (isset($data['firebase_token'])) {
            $token_verification = $this->compacts['data']->token_verification;
            $user = $this->repository->getUserByToken($token_verification);
            $user->firebase_token = $data['firebase_token'];
            $user->save();            
        }

        return $this->jsonRender();
    }

    public function refreshToken(RefreshTokenRequest $request, SocialInterface $service)
    {
        $requestData = $request->only('refresh_token');

        $this->compacts['data'] = $service->getUserByRefreshToken($requestData['refresh_token']);
        $this->compacts['description'] = 'Signed in successfully.';

        return $this->jsonRender();
    }
}
