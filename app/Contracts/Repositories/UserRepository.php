<?php

namespace App\Contracts\Repositories;

interface UserRepository extends AbstractRepository
{
    public function checkActiveAccount($email);

    public function getUserByEmail($email, array $dataSelect = ['*']);
}
