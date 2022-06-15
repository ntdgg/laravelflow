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

declare(strict_types=1);

namespace LaravelFlow\Custom\Laravel;

use DB;

class AdapteeEvent
{

    /**
     * 工作流审批日志记录
     *
     **/
    function add($data)
    {
        $ret = DB::table('wf_event')->insertGetId($data);
        if (!$ret) {
            return false;
        }
        return $ret;
    }
    function find($where = [])
    {
        return (array)DB::table('wf_event')->where($where)->first();
    }
    function select($where = [])
    {
        return DB::table('wf_event')->where($where)->get()->map(function ($value) {
            return (array)$value;
        })->toArray();
    }
    function save($data, $uid)
    {
        $find = (array)DB::table('wf_event')->where('type', $data['type'])->where('act', $data['fun'])->first();
        if ($find) {
            $post = [
                'code' => $data['code'],
                'uptime' => time()
            ];
            $ret = DB::table('wf_event')->where('id', $find['id'])->update($post);
            if (!$ret) {
                return ['code' => 1, 'msg' => '更新失败！'];
            }
        } else {
            $post = [
                'type' => $data['type'],
                'act' => $data['fun'],
                'uid' => $uid,
                'code' => $data['code'],
                'uptime' => time()
            ];
            $ret = DB::table('wf_event')->insertGetId($post);
            if (!$ret) {
                return ['code' => 1, 'msg' => '更新失败！'];
            }
        }
        return ['code' => 0, 'msg' => '更新失败！'];
    }
}
