<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Contracts\Repositories\ObjectiveRepository;
use App\Http\Requests\Api\Objective\UpdateObjectiveRequest;
use App\Http\Requests\Api\Objective\CreateObjectiveRequest;

class ObjectiveController extends ApiController
{
    protected $objectiveRepository;

    /**
     * Create a new controller instance.
     * @return void
    **/
    public function __construct(ObjectiveRepository $objectiveRepository)
    {
        parent::__construct();
        $this->objectiveRepository = $objectiveRepository;
    }

    /**
     * Display a listing of the resource by group id
     * @param  int  $groupId
     * @return \Illuminate\Http\Response
     */
    public function index($groupId, Request $request)
    {
        $quarter = $request->query('quarter');

        if (isset($quarter)) {
            return $this->doAction(function () use ($groupId, $quarter) {
                $this->compacts['data'] = $this->objectiveRepository->getObjectiveByQuarter($groupId, $quarter);
            });
        } else {
            return $this->getData(function () use ($groupId) {
                $this->compacts['data'] = $this->objectiveRepository->getObjectiveByGroup($groupId);
            });
        }
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $groupId
     */
    public function store($groupId, CreateObjectiveRequest $request)
    {
        $data = $request->only(
            'name',
            'description',
            'unit_id',
            'quarter_id',
            'parent_id'
        );

        return $this->doAction(function () use ($groupId, $data) {
            $this->compacts['data'] = $this->objectiveRepository->create($groupId, $data);
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
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateObjectiveRequest $request, $groupId, $objectiveId)
    {
        $data = $request->only(
            'actual'
        );

        return $this->doAction(function () use ($groupId, $objectiveId, $data) {
            $this->compacts['data'] = $this->objectiveRepository->updateObjectiveActual($groupId, $objectiveId, $data);
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
    }
}
