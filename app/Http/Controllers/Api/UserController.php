<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Contracts\Repositories\UserRepository;
use App\Http\Requests\Api\User\RequestUser;

class UserController extends ApiController
{
    protected $userRepository;

    /**
     * Create a new controller instance.
     * @return void
     **/
    public function __construct(UserRepository $userRepository)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->getData(function () {
            $this->compacts['data'] = $this->userRepository->getAll();
        });
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RequestUser $request)
    {
        $data = $request->only(
            'name',
            'email',
            'password',
            'code',
            'birthday',
            'gender',
            'phone',
            'mission',
            'avatar',
            'status'
        );
        
        return $this->doAction(function () use ($data) {
            $this->compacts['data'] = $this->userRepository->create($data);
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
            $this->compacts['data'] = $this->userRepository->show($id);
        });
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RequestUser $request, $id)
    {
        $data = $request->only(
            'name',
            'email',
            'password',
            'code',
            'birthday',
            'gender',
            'phone',
            'mission',
            'avatar',
            'status'
        );

        return $this->doAction(function () use ($id, $data) {
            $this->compacts['data'] = $this->userRepository->update($id, $data);
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
            $this->compacts['data'] = $this->userRepository->delete($id);
            $this->compacts['description'] = translate('success.delete');
        });
    }
}
