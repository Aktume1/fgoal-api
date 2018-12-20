<?php

namespace App\Contracts\Repositories;

interface QuarterRepository extends AbstractRepository
{
    public function getQuarter();

    public function create($data);

    public function show($id);

    public function update($id, $data);

    public function delete($id);
}
