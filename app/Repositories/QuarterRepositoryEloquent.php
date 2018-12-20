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
     * Create quarter
     * 
     * @param array $data
     * @return Quarter
     */
    public function create($data)
    {
        $quarter = $this->model()->create([
            'name' => $data['name'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
        ]);

        return $this->findOrFail($quarter->id);
    }

    /**
     * Show quarter
     * 
     * @param $id
     * @return Objective
     */
    public function show($id)
    {
        return $this->findOrFail($id);
    }

    /**
     * Update quarter
     * 
     * @param $id
     * @return Objective
     */
    public function update($id, $data)
    {
        $quarter = $this->findOrFail($id);
        $quarter->update($data);

        return $quarter;
    }

    /**
     * Delete quarter
     * 
     * @param $id
     * @return Objective
     */
    public function delete($id)
    {
        $quarter = $this->findOrFail($id);
        $quarter->delete();

        return $quarter;
    }
}
