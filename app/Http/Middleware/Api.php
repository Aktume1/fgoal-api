<?php

namespace App\Http\Middleware;

use Closure;
use App\Contracts\Repositories\UserRepository;
use App\Exceptions\Api\UnknownException;
use Auth;

class Api
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->header('Authorization');
        $userFromToken = $this->userRepository->getUserByToken($token);
        if (!$userFromToken) {
            throw new UnknownException(translate('http_message.unauthorized'), 401);
        }
        Auth::guard('fauth')->setUser($userFromToken);
        
        return $next($request);
    }
}
