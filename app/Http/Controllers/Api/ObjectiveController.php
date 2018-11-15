<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\Objective\CommentObjectiveRequest;
use App\Http\Requests\Api\Objective\UpdateContentRequest;
use Illuminate\Http\Request;
use App\Contracts\Repositories\ObjectiveRepository;
use App\Http\Requests\Api\Objective\UpdateObjectiveRequest;
use App\Http\Requests\Api\Objective\CreateObjectiveRequest;
use App\Http\Requests\Api\Objective\LinkObjectiveRequest;

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
     * @param  int $groupId
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
     * @param  \App\Http\Requests\Api\Objective\reateObjectiveRequest $request
     * @param  int $groupId
     */
    public function store($groupId, CreateObjectiveRequest $request)
    {
        $data = $request->only(
            'name',
            'description',
            'weight',
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
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function showObjective($groupId, $objectiveId)
    {
        return $this->getData(function () use ($groupId, $objectiveId) {
            $this->compacts['data'] = $this->objectiveRepository->showObjectiveDetail($groupId, $objectiveId);
        });
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
     * @param  \App\Http\Requests\Api\Objective\UpdateObjectiveRequest $request
     * @param  int $id
     * @param int $groupId
     * @param int $objectiveId
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
     * @param UpdateContentRequest $request
     * @param $objectiveId
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\Api\ActionException
     * @throws \App\Exceptions\Api\NotFoundException
     * @throws \App\Exceptions\Api\NotOwnerException
     * @throws \App\Exceptions\Api\UnknownException
     */
    public function updateContent(UpdateContentRequest $request, $groupId, $objectiveId)
    {
        $data = $request->name;

        return $this->doAction(function () use ($objectiveId, $groupId, $data) {
            $this->compacts['data'] = $this->objectiveRepository->updateContent($objectiveId, $groupId, $data);
            $this->compacts['description'] = translate('success.update');
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($groupId, $objectiveId)
    {
        return $this->doAction(function () use ($groupId, $objectiveId) {
            $this->compacts['data'] = $this->objectiveRepository->deleteObjective($groupId, $objectiveId);
            $this->compacts['description'] = translate('success.delete');
        });
    }

    /**
     * Link Objective To Parent Group's Key Result
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function linkObjective(LinkObjectiveRequest $request, $groupId)
    {
        $data = $request->only(
            'objectiveId',
            'keyResultId'
        );

        return $this->doAction(function () use ($groupId, $data) {
            $this->compacts['data'] = $this->objectiveRepository->linkObjectiveToKeyResult($groupId, $data);
            $this->compacts['description'] = translate('success.update');
        });
    }

    /**
     * Add link on objective
     *
     * @param $groupId
     * @param $objectiveId
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\Api\ActionException
     * @throws \App\Exceptions\Api\NotFoundException
     * @throws \App\Exceptions\Api\NotOwnerException
     * @throws \App\Exceptions\Api\UnknownException
     */
    public function removeLinkObjective($groupId, $objectiveId)
    {
        return $this->doAction(function () use ($groupId, $objectiveId) {
            $this->compacts['data'] = $this->objectiveRepository->removeLinkedObjective($groupId, $objectiveId);
            $this->compacts['description'] = translate('success.update');
        });
    }

    public function removeLinkObjectiveAccepted($groupId, $objectiveId)
    {
        return $this->doAction(function () use ($groupId, $objectiveId) {
            $this->compacts['data'] = $this->objectiveRepository->removeLinkObjectiveAccepted($groupId, $objectiveId);
            $this->compacts['description'] = translate('success.update');
        });
    }

    /**
     * Match Objective's Actual With Estimate
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function matchActualWithEstimate($groupId, $objectiveId)
    {
        return $this->doAction(function () use ($groupId, $objectiveId) {
            $this->compacts['data'] = $this->objectiveRepository->matchActualWithEstimate($groupId, $objectiveId);
            $this->compacts['description'] = translate('success.update');
        });
    }

    /**
     * List Objective log
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function logObjective($groupId, $objectiveId)
    {
        return $this->getData(function () use ($groupId, $objectiveId) {
            $this->compacts['data'] = $this->objectiveRepository->getObjectiveLogById($groupId, $objectiveId);
        });
    }

    /**
     * Admin verify link objective
     * @param $groupId
     * @param $objectiveId
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\Api\UnknownException
     */
    public function verifyLinkObjective($groupId, $objectiveId)
    {
        return $this->doAction(function () use ($groupId, $objectiveId) {
            $this->compacts['data'] = $this->objectiveRepository->verifyLink($groupId, $objectiveId);
            $this->compacts['description'] = translate('success.verify_link');
        });
    }

    /**
     * Verify all links request
     *
     * @param int $groupId
     * @param int $objectiveId
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\Api\ActionException
     * @throws \App\Exceptions\Api\NotFoundException
     * @throws \App\Exceptions\Api\NotOwnerException
     * @throws \App\Exceptions\Api\UnknownException
     */
    public function verifyAllLinkRequest($groupId, $objectiveId)
    {
        return $this->doAction(function () use ($groupId, $objectiveId) {
            $this->compacts['data'] = $this->objectiveRepository->verifyAllLink($groupId, $objectiveId);
            $this->compacts['description'] = translate('success.verify_link');
        });
    }

    /**
     * remove all links request
     *
     * @param int $groupId
     * @param int $objectiveId
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\Api\ActionException
     * @throws \App\Exceptions\Api\NotFoundException
     * @throws \App\Exceptions\Api\NotOwnerException
     * @throws \App\Exceptions\Api\UnknownException
     */
    public function removeAllLinkRequest($groupId, $objectiveId)
    {
        return $this->doAction(function () use ($groupId, $objectiveId) {
            $this->compacts['data'] = $this->objectiveRepository->removeAllLink($groupId, $objectiveId);
            $this->compacts['description'] = translate('success.cancel_link');
        });
    }
}

