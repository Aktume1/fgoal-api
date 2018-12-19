<?php

namespace App\Http\Controllers\Cms;

use Illuminate\Support\Facades\Input;
use App\Eloquent\Quarter;
use Session;

class QuarterController extends CmsController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = $this->requestToApi('/api/v1/quarters', 'GET');

        return view('cms.quarter.index', compact('data'));
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
        Session::flash('success', __('validation.addQuarter'));

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
        $data = $this->requestToApi('/api/v1/quarters/'. $id, 'GET');

        return view('cms.quarter.edit', compact('data'));
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
        $data = $this->requestToApi('/api/v1/quarters/'. $id, 'POST', Input::get());
        Session::flash('success', __('validation.updateQuarter'));

        return redirect()->route('quarters.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = $this->requestToApi('/api/v1/quarters/'. $id, 'DELETE', Input::get());
        Session::flash('success', __('validation.deleteQuarter'));

        return redirect()->route('quarters.index');
    }
}
