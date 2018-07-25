<?php

namespace App\Contracts\Services;

interface SocialInterface
{
    public function getUserFromWsm($email, $password);

    public function getAccessTokenFromWsm($email, $password);

    public function getUserInfo($accessToken);
    
    public function getGroupDetail($userFromAuthServer);
}
