<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Session;
use App\Eloquent\Unit;
use App\Http\Requests\Cms\RequestUnit;

class UnitController extends CmsController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = $this->requestToApi('/api/v1/units', 'GET');

        return view('cms.unit.index', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RequestUnit $request)
    {
        $data = $this->requestToApi('/api/v1/units', 'POST', Input::get());
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
        $data = $this->requestToApi('/api/v1/units/'. $id, 'GET');

        return view('cms.unit.edit', compact('data'));
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
        $data = $this->requestToApi('/api/v1/units/'. $id, 'POST', Input::get());
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
        $data = $this->requestToApi('/api/v1/units/'. $id, 'DELETE', Input::get());
        Session::flash('success', __('validation.deleteUnit'));

        return redirect()->route('units.index');
    }
}
