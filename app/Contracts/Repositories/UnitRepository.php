<?php

namespace App\Contracts\Repositories;

interface UnitRepository extends AbstractRepository
{
    public function getAll();

    public function create($data);

    public function update($id, $data);

    public function show($id);

    public function delete($id);
}
