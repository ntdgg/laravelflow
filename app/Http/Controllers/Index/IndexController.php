<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    /**
     * 显示给定用户的概要文件
     *
     * @param  int  $id
     * @return Response
     */
    public function index()
    {
        return view('index.index.index', []);
    }
    public function welcome()
    {
        return view('index.index.welcome', []);
    }
}
