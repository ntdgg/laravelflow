<?php

/**
 *+------------------
 * laravelflow 工作流日志消息
 *+------------------
 * Copyright (c) 2018~2025 liuzhiyun.com All rights reserved.  本版权不可删除，侵权必究
 *+------------------
 * Author: guoguo(1838188896@qq.com)
 *+------------------
 */
declare (strict_types=1);

namespace laravelflow\custom\laravel;

use DB;

class AdapteeLog
{

	/**
	 * 工作流审批日志记录
	 *
	 **/
	function AddrunLog($data)
	{
		$ret = DB::table('wf_run_log')->insertGetId($data);
		if (!$ret) {
			return false;
		}
		return $ret;
	}

	function SearchRunLog($wf_fid, $wf_type)
	{
		return DB::table('wf_run_log')->where('from_id', $wf_fid)->where('from_table', $wf_type)->order('id desc')->get()->map(function ($value) {return (array)$value;})->toArray();
	}


}
