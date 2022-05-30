<?php
/*
 * 公司新闻模块
 * @2018年1月21日
 * @Gwb
 */

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;

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
    public function add(Request $request)
    {
        if ($request->isMethod('post')) {
            $input = $request->post();
            unset($input['_token']);
            $input['uid'] = 1;
            $res = DB::table('news')->insert($input);
            if($res){
                return json_encode(['code'=>0,'msg'=>'success']);
            }else{
                return json_encode(['code'=>1,'msg'=>'err']);
            }
        }
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
    public function edit($id,Request $request)
    {
        if ($request->isMethod('post')) {
            $input = $request->post();
            unset($input['_token']);
            $res = DB::table('news')->where('id',$id)->update($input);
            if($res){
                return json_encode(['code'=>0,'msg'=>'success']);
            }else{
                return json_encode(['code'=>1,'msg'=>'err']);
            }
        }
        $info = DB::table('news')->find($id);
        return view('index.news.add',compact('info'));
    }

}
