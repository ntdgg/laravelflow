<?php
/**
 *+------------------
 * laravelflow 核心控制器
 *+------------------
 * Copyright (c) 2018~2025 liuzhiyun.com All rights reserved.  本版权不可删除，侵权必究
 *+------------------
 * Author: guoguo(1838188896@qq.com)
 *+------------------
 */
declare (strict_types = 1);

namespace laravelflow\service;

use laravelflow\lib\unit;


Class Control{

	protected $mode ;
    public function  __construct(){
		if(unit::gconfig('view_return')==1){
			$className = '\\laravelflow\\service\\method\\Tpl';
		}else{
			$className = '\\laravelflow\\service\\method\\Jwt';
		}
		$this->mode = new $className();
    }
	/**
	  * 工作流程统一接口
	  *
      * @access static
      * @param string $act 调用接口方法
	  * 调用 laravelflow\adaptive\Control 的核心适配器进行API接口的调用
	  * Info    获取流程信息
	  * start   发起审批流
	  * endflow 审批流终止
	  *
	  */
	static function WfCenter($act,$wf_fid='',$wf_type='',$data='',$post=''){
		return (new Control())->mode->WfCenter($act,$wf_fid,$wf_type,$data,$post);
	}
	/**
	 * laravelflow1.0统一接口 流程管理中心
	 * @param string $act 调用接口方法
	 * 调用 laravelflow\adaptive\Control 的核心适配器进行API接口的调用
	 * welcome 调用版权声明接口
	 * check   调用逻辑检查接口
	 * add     新增步骤接口
	 * wfdesc  设计界面接口
	 * save    保存数据接口
	 * del     删除数据接口
	 * delAll  删除所有步骤接口
	 * att     调用步骤属性接口
	 * saveatt 保存步骤属性接口
	 */
	static function WfFlowCenter($act,$data=''){
		return (new Control())->mode->WfFlowCenter($act,$data);
	}
	/**
	 * laravelflow1.0 工作流代理接口
	 * @param string $act 调用接口方法
	 * 调用 laravelflow\adaptive\Control 的核心适配器进行API接口的调用
	 * index 列表调用
	 * add   添加代理授权
	 */
	static function WfEntrustCenter($act,$data=''){
		return (new Control())->mode->WfEntrustCenter($act,$data);
	}
	/**
	 * laravelflow1.0统一接口设计器
	 * @param string $act 调用接口方法
	 * 调用 laravelflow\adaptive\Control 的核心适配器进行API接口的调用
	 * welcome 调用版权声明接口
	 * check   调用逻辑检查接口
	 * add     新增步骤接口
	 * wfdesc  设计界面接口
	 * save    保存数据接口
	 * del     删除数据接口
	 * delAll  删除所有步骤接口
	 * att     调用步骤属性接口
	 * saveatt 保存步骤属性接口
	 * super_user 用户选择控件
	 */
	static function WfDescCenter($act,$flow_id='',$data=''){
		return (new Control())->mode->WfDescCenter($act,$flow_id,$data);

	}
	/**
	 * laravelflow1.0统一接口
	 * @param string $act 调用接口方法
	 * 调用 laravelflow\adaptive\Control 的核心适配器进行API接口的调用
	 * log  历史日志消息
	 * btn  权限判断
	 * status  状态判断
	 */
	static function WfAccess($act,$data=''){
		echo (new Control())->mode->WfAccess($act,$data);
	}
	/**
	 * laravelflow1.0统一接口
	 * @param string $act 调用接口方法
	 * 调用 laravelflow\adaptive\Control 的核心适配器进行API接口的调用
	 * log  历史日志消息
	 * btn  权限判断
	 * status  状态判断
	 */
	static function wfUserData($act,$map,$field,$order,$group,$page,$limit){
		return (new Control())->mode->wfUserData($act,$map,$field,$order,$group,$page,$limit);
	}
    /**
     * laravelflow 6.0统一接口
     * @param string $act 调用接口方法
     */
    static function wfMysend($page,$limit){
        return (new Control())->mode->wfMysend($page,$limit);
    }

}
