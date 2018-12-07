<?php

namespace App\Repositories;

use App\Contracts\Repositories\QuarterRepository;
use App\Eloquent\Quarter;
use Carbon\Carbon;

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
        $quarters =  $this->all();
        $curentQuarter = null;

        $now = Carbon::now();
        $nowFormat = date_format($now, 'Y-m-d');
        $curentQuarter = $this->whereDate('start_date', '<=', $nowFormat)->whereDate('end_date', '>=', $nowFormat)
                    ->first();

        $data['current_quarter'] = $curentQuarter;
        $data['quarters'] = $quarters;
        
        return $data;
    }

    /**
     * Create Objective
     * @param int $groupId
     * @param array $data
     * @return Objective
     */
    public function create($data)
    {
        $quarter = $this->model()->create([
            'name' => $data['name'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
        ]);

        $quarterCreated = $this->findOrFail($quarter->id);

        return $quarterCreated;
    }
}
