<?php
/*
 * 公司新闻模块
 * @2018年1月21日
 * @Gwb
 */

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;

class NewsController extends Controller
{
    /**
     * 新闻列表
     */
    public function index()
    {
        return view('index.news.index', []);
    }

    /**
     * 新增新闻
     */
    public function add()
    {

    }
    /**
     * 查看新闻
     */
    public function view()
    {

    }
    /**
     * 修改新闻
     */
    public function edit()
    {

    }

}
