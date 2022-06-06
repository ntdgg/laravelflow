<?php
/**
 *+------------------
 * laravelflow Run
 *+------------------
 * Copyright (c) 2006~2020 http://cojz8.cn All rights reserved.
 *+------------------
 * Author: guoguo(1838188896@qq.com)
 *+------------------
 */
declare (strict_types=1);

namespace laravelflow\custom\laravel;

use DB;

class AdapteeRun
{

	function AddRun($data)
	{
		return DB::table('wf_run')->insertGetId($data);
	}

	function FindRun($where = [], $field = '*')
	{
		return (array)DB::table('wf_run')->where($where)->first();
	}

	function FindRunId($id, $field = '*')
	{
		return (array)DB::table('wf_run')->find($id);
	}

	function SearchRun($where = [], $field = '*')
	{
		return DB::table('wf_run')->where($where)->get()->map(function ($value) {return (array)$value;})->toArray();
	}

	function EditRun($id, $data)
	{
		return DB::table('wf_run')->where('id', $id)->update($data);
	}

	/*run_process表操作接口代码*/
	function FindRunProcessId($id, $field = '*')
	{
		return (array)DB::table('wf_run_process')->find($id);
	}

	function FindRunProcess($where = [], $field = '*')
	{
		return (array)DB::table('wf_run_process')->where($where)->first();
	}

	function AddRunProcess($data)
	{
		return DB::table('wf_run_process')->insertGetId($data);
	}

	function SearchRunProcess($where = [], $field = '*')
	{
		return DB::table('wf_run_process')->where($where)->get()->map(function ($value) {return (array)$value;})->toArray();
	}

	function EditRunProcess($id, $data = [])
	{
		return DB::table('wf_run_process')->whereIn('id',explode(',',$id))->update($data);
	}

	/*FindRunSign表操作接口代码*/
	function FindRunSign($where = [], $field = '*')
	{
		return (array)DB::table('wf_run_sign')->where($where)->first();
	}

	function AddRunSing($data)
	{
		return DB::table('wf_run_sign')->insertGetId($data);
	}

	function EndRunSing($sing_sign, $check_con)
	{
		return DB::table('wf_run_sign')->where('id', $sing_sign)->update(['is_agree' => 1, 'content' => $check_con, 'dateline' => time()]);
	}

    function dataRunProcess($map, $mapRaw,$field, $order,$page,$limit)
    {
        $offset = ($page-1)*$limit;
        return DB::table('wf_run_process')->alias('f')->join('wf_flow w', 'f.run_flow = w.id')->join('wf_run r', 'f.run_id = r.id')->where($map)->whereRaw($mapRaw)->limit($offset,(int)$limit)->order($order)->get()->map(function ($value) {return (array)$value;})->toArray();
    }

    function dataRunMy($uid,$page,$limit)
    {
        $offset = ($page-1)*$limit;
        $data = DB::table('wf_run_process')->alias('f')->join('wf_flow w', 'f.run_flow = w.id')->join('wf_run r', 'f.run_id = r.id')->where('r.uid',$uid)->limit($offset,(int)$limit)->group('r.id')->order('r.id desc')->get()->map(function ($value) {return (array)$value;})->toArray();;
        $count = DB::table('wf_run_process')->alias('f')->join('wf_flow w', 'f.run_flow = w.id')->join('wf_run r', 'f.run_id = r.id')->where('r.uid',$uid)->group('r.id')->count();
        return ['data'=>$data,'count'=>$count];
    }

	function dataRunProcessGroup($map, $field, $order, $group)
	{
		return DB::table('wf_run_process')->alias('f')->join('wf_flow w', 'f.run_flow = w.id')->join('wf_run r', 'f.run_id = r.id')->where($map)->order($order)->group($group)->get()->map(function ($value) {return (array)$value;})->toArray();;
	}
}
