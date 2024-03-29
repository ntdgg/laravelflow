<?php

/**
 *+------------------
 * LaravelFlow 流信息处理
 *+------------------
 * Copyright (c) 2018~2025 liuzhiyun.com All rights reserved.  本版权不可删除，侵权必究
 *+------------------
 * Author: guoguo(1838188896@qq.com)
 *+------------------
 */

declare(strict_types=1);

namespace LaravelFlow\Custom\Laravel;

use DB;
use LaravelFlow\Lib\Unit;


class AdapteeInfo
{

    /**
     * 接入工作流的类别
     *
     */
    function get_wftype()
    {
        if (Unit::gconfig('wf_type_mode') == 0) {
            $data =  (array)DB::select("select replace(TABLE_NAME,'" . Unit::gconfig('prefix') . "','')as name,TABLE_COMMENT as title from information_schema.tables where table_schema='" . Unit::gconfig('database') . "' and TABLE_COMMENT like '" . Unit::gconfig('work_table') . "%' and TABLE_NAME not like '%_bak';");
            $type = [];
            foreach ($data as $v) {
                $type[] = (array)$v;
            }
            return $type;
        } else {
            return Unit::gconfig('wf_type_data');
        }
    }
}
