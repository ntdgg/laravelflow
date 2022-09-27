<?php

/**
 *+------------------
 * LaravelFlow 工作流步骤
 *+------------------
 * Copyright (c) 2018~2025 liuzhiyun.com All rights reserved.  本版权不可删除，侵权必究
 *+------------------
 * Author: guoguo(1838188896@qq.com)
 *+------------------
 */

declare(strict_types=1);

namespace LaravelFlow\Custom\Laravel;

use DB;

class AdapteeProcess
{

    function find($id, $field = '*')
    {
        return (array)DB::table('wf_flow_process')->find($id);
    }

    function finds($ids, $field = '*')
    {
        return DB::table('wf_flow_process')->field($field)->where('id', 'in', $ids)->get()->map(function ($value) {
            return (array)$value;
        })->toArray();
    }

    function SearchFlowProcess($where = [], $field = '*', $order = '', $limit = 0)
    {
        if ($limit > 0) {
            return DB::table('wf_flow_process')->where($where)->limit($limit)->get()->map(function ($value) {
                return (array)$value;
            })->toArray();
        } else {
            return DB::table('wf_flow_process')->where($where)->get()->map(function ($value) {
                return (array)$value;
            })->toArray();
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
        $dt = DB::table('wf_flow_process as f')
            ->join('wf_flow', 'f.flow_id', '=', 'wf_flow.id')
            ->select('f.id', 'f.process_name', 'f.flow_id', 'wf_flow.flow_name', 'f.auto_sponsor_ids', 'f.range_user_ids')
            ->whereRaw('FIND_IN_SET(' . $uid . ',`auto_sponsor_ids`)')
            ->orWhereRaw('FIND_IN_SET(' . $uid . ',`range_user_ids`)');
        foreach ((array)$role as $k => $v) { // Guoke 2021/11/26 13:38 扩展多多用户组的支持
            $dt->whereOr("find_in_set('.$v.',`auto_role_ids`)");
        }
        return $dt->get()->map(function ($value) {
            return (array)$value;
        })->toArray();
    }
}
