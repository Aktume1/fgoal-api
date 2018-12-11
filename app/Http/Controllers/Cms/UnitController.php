<?php

namespace App\Http\Controllers\Cms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Session;
use App\Eloquent\Unit;
use App\Http\Requests\Cms\RequestUnit;
use Illuminate\Support\Facades\Route;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $request = Request::create('/api/v1/units', 'GET');
        $request->headers->set('Authorization', 'ae8m0R6WPLT8ZxWDrLIiSh1h4Qsait5wGbL2ckIhOQwWCk7yNb19ztxYhls1');
        $response = Route::dispatch($request);
        $units = $response->getData();
        $data = $units->data;

        return view('cms.unit.index', compact('data'));
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
        $request = Request::create('/api/v1/units', 'POST', Input::get());
        $request->headers->set('Authorization', 'ae8m0R6WPLT8ZxWDrLIiSh1h4Qsait5wGbL2ckIhOQwWCk7yNb19ztxYhls1');
        $response = Route::dispatch($request);
        Session::flash('success', __('validation.createUnit'));

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $request = Request::create('/api/v1/units/'. $id, 'GET');
        $request->headers->set('Authorization', 'ae8m0R6WPLT8ZxWDrLIiSh1h4Qsait5wGbL2ckIhOQwWCk7yNb19ztxYhls1');
        $response = Route::dispatch($request);
        $units = $response->getData();
        $data = $units->data;

        return view('cms.unit.edit', compact('data'));
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
        $request = Request::create('/api/v1/units/'. $id, 'POST', Input::get());
        $request->headers->set('Authorization', 'ae8m0R6WPLT8ZxWDrLIiSh1h4Qsait5wGbL2ckIhOQwWCk7yNb19ztxYhls1');
        $response = Route::dispatch($request);
        Session::flash('success', __('validation.updateUnit'));

        return redirect()->route('units.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $request = Request::create('/api/v1/units/'. $id, 'POST', Input::get());
        $request->headers->set('Authorization', 'ae8m0R6WPLT8ZxWDrLIiSh1h4Qsait5wGbL2ckIhOQwWCk7yNb19ztxYhls1');
        $response = Route::dispatch($request);
        Session::flash('success', __('validation.deleteUnit'));

        return redirect()->route('units.index');
    }
}
