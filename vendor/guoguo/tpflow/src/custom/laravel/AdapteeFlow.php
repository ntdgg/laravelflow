<?php
/**
 *+------------------
 * Tpflow 流信息处理
 *+------------------
 * Copyright (c) 2018~2025 liuzhiyun.com All rights reserved.  本版权不可删除，侵权必究
 *+------------------
 * Author: guoguo(1838188896@qq.com)
 *+------------------
 */
declare (strict_types=1);

namespace tpflow\custom\laravel;

use DB;
use tpflow\lib\unit;

class AdapteeFlow
{

	function find($id, $field = '*')
	{
		return (array)DB::table('wf_flow')->find($id);
	}

    function del($id)
    {
        return DB::table('wf_flow')->delete($id);
    }

	function AddFlow($data)
	{
		return DB::table('wf_flow')->insertGetId($data);
	}

	function EditFlow($data)
	{
        $data['add_time'] = time();
		return DB::table('wf_flow')->update($data);
	}

	function SearchFlow($where = [], $field = '*')
	{
		return DB::table('wf_flow')->where($where)->get()->map(function ($value) {return (array)$value;})->toArray();
	}

	function ListFlow($map, $page, $rows, $order)
	{
		$offset = ($page - 1) * $rows;
		$list = DB::table('wf_flow')->where($map)->orderBy('id','desc')->limit($rows)->offset($offset)->get()->map(function ($value) {return (array)$value;})->toArray();
		$count = DB::table('wf_flow')->where($map)->count();
		return ['total' => $count, 'rows' => $list];
	}

	function get_db_column_comment($table_name = '', $field = true, $table_schema = '')
	{
		$table_schema = empty($table_schema) ? unit::gconfig('database') : $table_schema;
		$table_name = unit::gconfig('prefix') . $table_name;
		$fieldName = $field === true ? 'allField' : $field;
		$cacheKeyName = 'db_' . $table_schema . '_' . $table_name . '_' . $fieldName;
		$param = [
			$table_name,
			$table_schema
		];
		$columeName = '';
		if ($field !== true) {
			$param[] = $field;
			$columeName = "AND COLUMN_NAME = ?";
		}
		$res = DB::select("SELECT COLUMN_NAME as field,column_comment as comment FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = ? AND table_schema = ? $columeName", $param);
		$result = array();

		foreach ($res as $k => $value) {
            $value =(array)$value;
            if ($value['comment'] != '') {
                $result[$value['field']] = $value['comment'];
            }
		}
		return count($result) == 1 ? reset($result) : $result;
	}
}
