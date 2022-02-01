<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use DB;

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
        $list = DB::table('user')->get();
        return view('index.index.index', compact('list'));
    }
    public function welcome()
    {
        return view('index.index.welcome', []);
    }
    public function login($id,$name){
        session(['uid' => $id]);
        session(['uname' => $name]);
       return redirect('/');
    }
}
