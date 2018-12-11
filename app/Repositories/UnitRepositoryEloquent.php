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

    public function create($data)
    {
        $unit = $this->model()->create([
            'unit' => $data['unit'],
        ]);

        return $this->findOrFail($unit->id);
    }

    public function show($id)
    {
        $unit = $this->findOrFail($id);

        return $unit;
    }


    public function update($id, $data)
    {
        $unit = $this->findOrFail($id);
        $unit->update($data);

        return $unit;
    }

    public function delete($id)
    {
        $unit = $this->findOrFail($id);
        $unit->delete();

        return $unit;
    }
}
