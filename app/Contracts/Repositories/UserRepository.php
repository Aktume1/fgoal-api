<?php

namespace App\Contracts\Repositories;

interface UserRepository extends AbstractRepository
{
    public function getAll();

    public function create($data);

    public function show($id);

    public function update($id, $data);

    public function delete($id);
    
    public function checkActiveAccount($email);

    public function getUserByEmail($email, array $dataSelect = ['*']);

    public function getUserByToken($token);
}
