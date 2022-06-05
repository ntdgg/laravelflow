<?php
/**
 *+------------------
 * Tpflow 工作流步骤
 *+------------------
 * Copyright (c) 2018~2025 liuzhiyun.com All rights reserved.  本版权不可删除，侵权必究
 *+------------------
 * Author: guoguo(1838188896@qq.com)
 *+------------------
 */
declare (strict_types=1);

namespace tpflow\custom\laravel;

use DB;

class AdapteeProcess
{

	function find($id, $field = '*')
	{
		return (array)DB::table('wf_flow_process')->find($id);
	}

	function finds($ids, $field = '*')
	{
		return DB::table('wf_flow_process')->field($field)->where('id', 'in', $ids)->get()->map(function ($value) {return (array)$value;})->toArray();
	}

	function SearchFlowProcess($where = [], $field = '*', $order = '', $limit = 0)
	{
		if ($limit > 0) {
			return DB::table('wf_flow_process')->where($where)->limit($limit)->get()->map(function ($value) {return (array)$value;})->toArray();
		} else {
			return DB::table('wf_flow_process')->where($where)->get()->map(function ($value) {return (array)$value;})->toArray();
		}
	}

	function EditFlowProcess($where, $data)
	{
		return DB::table('wf_flow_process')->where($where)->update($data);
	}

	function DelFlowProcess($where)
	{
		return DB::table('wf_flow_process')->where($where)->delete();
	}

	function AddFlowProcess($data)
	{
		return DB::table('wf_flow_process')->insertGetId($data);
	}

	function get_userprocess($uid, $role)
	{
		$dt = DB::table('wf_flow_process')
			->alias('f')
			->join('wf_flow w', 'f.flow_id = w.id')
			->field('f.id,f.process_name,f.flow_id,w.flow_name')
			->where('find_in_set(:asi,f.auto_sponsor_ids)', ['asi' => $uid])
			->whereOr('find_in_set(:rui,f.range_user_ids)', ['rui' => $uid]);
		foreach((array)$role as $k=>$v){// Guoke 2021/11/26 13:38 扩展多多用户组的支持
			$dt->whereOr("find_in_set(:ari$k,f.auto_role_ids)", ["ari$k" => $v]);
		}
		return $dt->get()->map(function ($value) {return (array)$value;})->toArray();
	}
}
