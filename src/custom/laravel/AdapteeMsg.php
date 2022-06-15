<?php

/**
 *+------------------
 * LaravelFlow 工作流日志消息
 *+------------------
 * Copyright (c) 2018~2025 liuzhiyun.com All rights reserved.  本版权不可删除，侵权必究
 *+------------------
 * Author: guoguo(1838188896@qq.com)
 *+------------------
 */
declare (strict_types=1);

namespace LaravelFlow\Custom\Laravel;

use DB;

class AdapteeMsg
{
    /**
     * 工作流审批日志记录
     *
     **/
    function add($data)
    {
        $ret = DB::table('wf_run_process_msg')->insertGetId($data);
        if (!$ret) {
            return false;
        }
        return $ret;
    }
    function update($map)
    {
        return DB::table('wf_run_process_msg')->where($map)->update(['status'=>2,'uptime'=>time()]);
    }
    function findWhere($map)
    {
        return (array)DB::table('wf_run_process_msg')->where($map)->first();
    }


}
