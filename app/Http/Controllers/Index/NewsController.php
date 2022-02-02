<?php
/*
 * 公司新闻模块
 * @2018年1月21日
 * @Gwb
 */

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use DB;

class NewsController extends Controller
{
    /**
     * 新闻列表
     */
    public function index()
    {
        $list = DB::table('news')->simplePaginate(10);
        return view('index.news.index',compact('list'));
    }

    /**
     * 新增新闻
     */
    public function add()
    {
        return view('index.news.add');
    }
    /**
     * 查看新闻
     */
    public function view($id)
    {
        $info = DB::table('news')->find($id);
        return view('index.news.view',compact('info'));
    }
    /**
     * 修改新闻
     */
    public function edit()
    {

    }

}
