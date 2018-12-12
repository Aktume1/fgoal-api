<?php

namespace App\Http\Controllers\Cms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends CmsController
{
    public function index()
    {
        return view('cms.home.index');
    }
}
