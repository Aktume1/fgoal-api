<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Contracts\Repositories\QuarterRepository;
use App\Http\Requests\Api\Quarter\CreateQuarterRequest;

class QuarterController extends ApiController
{
    protected $quarterRepository;

    /**
     * Create a new controller instance.
     * @return void
     **/
    public function __construct(QuarterRepository $quarterRepository)
    {
        parent::__construct();
        $this->quarterRepository = $quarterRepository;
    }

    /**
     * Display a listing of the quarter
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->getData(function () {
            $this->compacts['data'] = $this->quarterRepository->getQuarter();
        });
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateQuarterRequest $request)
    {
        $data = $request->only(
            'name',
            'start_date',
            'end_date'
        );

        return $this->doAction(function () use ($data) {
            $this->compacts['data'] = $this->quarterRepository->create($data);
            $this->compacts['description'] = translate('success.create');
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }
}
