<?php

namespace App\Repositories;

use App\Eloquent\Unit;
use App\Contracts\Repositories\UnitRepository;

class UnitRepositoryEloquent extends AbstractRepositoryEloquent implements UnitRepository
{
    public function model()
    {
        return app(Unit::class);
    }

    public function getAll()
    {
    	return $this->get();
    }
}
