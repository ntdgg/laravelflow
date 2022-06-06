<?php
/**
 *+------------------
 * laravelflow 流信息处理
 *+------------------
 * Copyright (c) 2018~2025 liuzhiyun.com All rights reserved.  本版权不可删除，侵权必究
 *+------------------
 * Author: guoguo(1838188896@qq.com)
 *+------------------
 */
declare (strict_types=1);

namespace laravelflow\custom\laravel;

use DB;
use laravelflow\lib\unit;


class AdapteeInfo
{

	/**
	 * 接入工作流的类别
	 *
	 */
	function get_wftype()
	{
		if (unit::gconfig('wf_type_mode') == 0) {
			$data =  (array)DB::select("select replace(TABLE_NAME,'" . unit::gconfig('prefix') . "','')as name,TABLE_COMMENT as title from information_schema.tables where table_schema='" . unit::gconfig('database') . "' and TABLE_COMMENT like '" . unit::gconfig('work_table') . "%' and TABLE_NAME not like '%_bak';");
            $type = [];
            foreach($data as $v){
                $type[] = (array)$v;
            }
            return $type;
		} else {
			return unit::gconfig('wf_type_data');
		}

	}
}
