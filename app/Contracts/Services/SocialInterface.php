<?php

namespace App\Contracts\Services;

interface SocialInterface
{
    public function getUserByPasswordGrant($email, $password);

    public function getTokenByPasswordGrant($email, $password);

    public function getUserInfo($accessToken);
    
    public function getGroupDetail($userFromAuthServer);

    public function refreshToken($refreshToken);

    public function getUserByRefreshToken($refreshToken);
}
