<?php

namespace App\Http\Middleware;

use Closure;
use App\Eloquent\User;

class WebhookWSM
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $accessToken = isset($request->access_token) ? $request->access_token : null;
        if ($accessToken != config('settings.ACCESS_TOKEN_WebhookWSM')) {
            return response([
                        'code' => 404,
                        'description' => ['Unauthorized'],
                    ]);
        }

        return $next($request);
    }
}
