<?php

/**
 *+------------------
 * laravelflow 抄送处理
 *+------------------
 * Copyright (c) 2018~2025 liuzhiyun.com All rights reserved.  本版权不可删除，侵权必究
 *+------------------
 * Author: guoguo(1838188896@qq.com)
 *+------------------
 */

declare (strict_types=1);

namespace laravelflow\custom\laravel;

use DB;

class AdapteeCc
{
    /**
     * 添加
     *
     **/
    function add($data)
    {
        $ret = DB::table('wf_run_process_cc')->insertGetId($data);
        if (!$ret) {
            return false;
        }
        return $ret;
    }
    /**
     * 更新
     *
     **/
    function update($data)
    {
        return DB::table('wf_run_process_cc')->update($data);
    }
    /**
     * 查询
     *
     **/
    function findWhere($map)
    {
        return (array)DB::table('wf_run_process_cc')->where($map)->first();
    }
}
