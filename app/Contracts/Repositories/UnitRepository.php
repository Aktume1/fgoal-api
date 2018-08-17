<?php

namespace App\Contracts\Repositories;

interface UnitRepository extends AbstractRepository
{
    public function getAll();
}
