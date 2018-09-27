<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\Group\AddMemberRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Contracts\Repositories\GroupRepository;

class GroupController extends ApiController
{
    protected $repository;

    /**
     * Create a new controller instance.
     * @return void
     **/
    public function __construct(GroupRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * @param $groupId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getParentByGroupId($groupId)
    {
        return $this->getData(function () use ($groupId) {
            $this->compacts['data'] = $this->repository->getParentOfGroup($groupId);
        });
    }

    /**
     * @param $groupId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInfomationGroup(Request $request, $groupId)
    {
        $quarterId = $request->quarter;

        return $this->getData(function () use ($groupId, $quarterId) {
            $this->compacts['data'] = $this->repository->getInfomationGroup($groupId, $quarterId);
        });
    }

    public function getChildGroupsInfor($groupId)
    {
        return $this->getData(function () use ($groupId) {
            $this->compacts['data'] = $this->repository->getChildGroups($groupId);
        });
    }

    /**
     * @param $groupId
     * @param $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteUserFromGroup($groupId, $userId)
    {
        return $this->doAction(function () use ($groupId, $userId) {
            $this->compacts['data'] = $this->repository->deleteUserFromGroup($groupId, $userId);
            $this->compacts['description'] = translate('success.delete');
        });
    }

    public function getUserWithPer($groupId)
    {
        return $this->getData(function () use ($groupId) {
            $this->compacts['data'] = $this->repository->getUserWithPer($groupId);
        });
    }

    /**
     * Add member to group
     *
     * @param AddMemberRequest $request
     * @param $groupId
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\Api\UnknownException
     */
    public function addMemberGroup(AddMemberRequest $request, $groupId)
    {
        $data = $request->only(
            'email',
            'role'
        );

        return $this->doAction(function () use ($groupId, $data) {
            $this->compacts['data'] = $this->repository->addMember($groupId, $data);
            $this->compacts['description'] = translate('success.create');
        });
    }

    /**
     * Get group by user code
     *
     * @param $code
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\Api\UnknownException
     */
    public function getGroupByCode($code)
    {
        return $this->getData(function () use ($code) {
            $this->compacts['data'] = $this->repository->getGroupByCode($code);
        });
    }

    /**
     * Get process of group
     *
     * @param $groupId
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\Api\UnknownException
     */
    public function getProcessByGroupId($groupId, $quarterId)
    {
        return $this->getData(function () use ($groupId, $quarterId) {
            $this->compacts['data'] = $this->repository->getProcessById($groupId, $quarterId);
        });
    }
    
    public function getLogGroup($groupId)
    {
        return $this->getData(function () use ($groupId) {
            $this->compacts['data'] = $this->repository->getLogGroup($groupId);
        });
    }

    /**
     * Get logs group
     *
     * @param int $groupId
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\Api\UnknownException
     */
    public function getLogsGroup($groupId)
    {
        return $this->getData(function () use ($groupId) {
            $this->compacts['data'] = $this->repository->getLogsGroup($groupId);
        });
    }

    /**
     * @param int $groupId
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\Api\UnknownException
     */
    public function checkAdminGroup($groupId, $userId)
    {
        return $this->getData(function () use ($groupId, $userId) {
            $this->compacts['data'] = $this->repository->checkAdminGroup($groupId, $userId);
        });
    }

    /**
     * Get link request
     *
     * @param int $groupId
     * @param int $userId
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\Api\UnknownException
     */
    public function getLinkRequest($groupId)
    {
        return $this->getData(function () use ($groupId) {
            $this->compacts['data'] = $this->repository->getLinkRequest($groupId);
        });
    }

    /**
     * @param $groupId
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\Api\UnknownException
     */
    public function getTracking($groupId)
    {
        return $this->getData(function () use ($groupId) {
            $this->compacts['data'] = $this->repository->getTrackingByWeek($groupId);
        });
    }
}

