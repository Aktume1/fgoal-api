<?php

namespace App\Contracts\Repositories;

interface QuarterRepository extends AbstractRepository
{
    public function getQuarter();

    public function create($data);
}
