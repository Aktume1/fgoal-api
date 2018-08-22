<?php

namespace App\Repositories;

use App\Contracts\Repositories\QuarterRepository;
use App\Eloquent\Quarter;

class QuarterRepositoryEloquent extends AbstractRepositoryEloquent implements QuarterRepository
{
    public function model()
    {
        return app(Quarter::class);
    }

    /**
    * Return list quarter
    */
    public function getQuarter()
    {
        return $this->all();
    }
}
