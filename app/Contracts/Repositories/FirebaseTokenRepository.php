<?php

namespace App\Contracts\Repositories;

interface FirebaseTokenRepository extends AbstractRepository
{
    public function updateOrCreateFirebaseToken($data);
}
