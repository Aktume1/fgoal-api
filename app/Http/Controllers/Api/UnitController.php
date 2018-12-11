<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Contracts\Repositories\UnitRepository;
use App\Http\Requests\Api\Unit\RequestUnit;

class UnitController extends ApiController
{
    protected $unitRepository;

    /**
     * Create a new controller instance.
     * @return void
    **/
    public function __construct(UnitRepository $unitRepository)
    {
        parent::__construct();
        $this->unitRepository = $unitRepository;
    }

    /**
     * Display a listing of the resource by group id
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->getData(function () {
            $this->compacts['data'] = $this->unitRepository->getAll();
        });
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RequestUnit $request)
    {
        $data = $request->only(
            'unit'
        );
        
        return $this->doAction(function () use ($data) {
            $this->compacts['data'] = $this->unitRepository->create($data);
            $this->compacts['description'] = translate('success.create');
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->getData(function () use ($id) {
            $this->compacts['data'] = $this->unitRepository->show($id);
        });
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RequestUnit $request, $id)
    {
        $data = $request->only(
            'unit'
        );

        return $this->doAction(function () use ($id, $data) {
            $this->compacts['data'] = $this->unitRepository->update($id, $data);
            $this->compacts['description'] = translate('success.update');
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->doAction(function () use ($id) {
            $this->compacts['data'] = $this->unitRepository->delete($id);
            $this->compacts['description'] = translate('success.delete');
        });
    }
}
