<?php

declare (strict_types=1);

namespace laravelflow\custom\laravel;

use DB;

class AdapteeKpi
{
    function addKpi($data){

        DB::beginTransaction();
        try {
            DB::table('wf_kpi_data')->insertGetId($data);
            if($this->hasKpiMonth($data['k_uid'])){
                $this->incKpiMonth($data['k_uid'],$data['k_mark']);
                }else{
                $this->addKpiMonth($data);
            }
            if($this->hasKpiYear($data['k_uid'])){
                $this->incKpiYear($data['k_uid'],$data['k_mark']);
            }else{
                $this->addKpiYear($data);
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            // 回滚事务
            DB::rollBack();
            return false;
        }
    }

    function hasKpiYear($uid){
        $has = DB::table('wf_kpi_year')->where('k_uid',$uid)->where('k_year',date('Y'))->first();
        if($has){
            return true;
        }else{
            return false;
        }
    }

    function hasKpiMonth($uid){
        $has = DB::table('wf_kpi_month')->where('k_uid',$uid)->where('k_year',date('Y'))->where('k_month',date('m'))->first();
        if($has){
            return true;
        }else{
            return false;
        }
    }

    function addKpiYear($data){
        $post = [
            'k_uid'=>$data['k_uid'],
            'k_role'=>$data['k_role'],
            'k_mark'=>$data['k_mark'],
            'k_year'=>$data['k_year'],
            'k_time'=>1,
            'k_create_time'=>time(),
        ];
        DB::table('wf_kpi_year')->insertGetId($post);
    }

    function addKpiMonth($data){
        $post = [
            'k_uid'=>$data['k_uid'],
            'k_role'=>$data['k_role'],
            'k_mark'=>$data['k_mark'],
            'k_year'=>$data['k_year'],
            'k_month'=>$data['k_month'],
            'k_time'=>1,
            'k_create_time'=>time(),
        ];
        DB::table('wf_kpi_month')->insertGetId($post);
    }

    function incKpiYear($uid,$mark){
        DB::table('wf_kpi_year')->where('k_uid',$uid)->where('k_year',date('Y'))->increment('k_mark', (int)$mark, ['k_time'=>DB::raw('k_time + 1')]);
    }

    function incKpiMonth($uid,$mark){
        DB::table('wf_kpi_month')->where('id',$uid)->where('k_year',date('Y'))->where('k_month',date('m'))->increment('k_mark', $mark, ['k_time'=>DB::raw('k_time + 1')]);
    }
}
