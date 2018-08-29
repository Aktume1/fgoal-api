<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\CommentRepository;
use App\Http\Requests\Api\Objective\CommentObjectiveRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentController extends ApiController
{
    protected $commentRepository;

    /**
     * Create a new controller instance.
     * @return void
     **/
    public function __construct(CommentRepository $commentRepository)
    {
        parent::__construct();
        $this->commentRepository = $commentRepository;
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

    }

    /**
     * @param CommentObjectiveRequest $request
     * @param $objectiveId
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\Api\ActionException
     * @throws \App\Exceptions\Api\NotFoundException
     * @throws \App\Exceptions\Api\NotOwnerException
     * @throws \App\Exceptions\Api\UnknownException
     */
    public function store(CommentObjectiveRequest $request, $objectiveId)
    {
        $data = $request->only(
            'content'
        );

        return $this->doAction(function () use ($objectiveId, $data) {
            $this->compacts['data'] = $this->commentRepository->commentObjective($objectiveId, $data);
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
}
