<?php

/**
 *+------------------
 * LaravelFlow 1.0 系
 *+------------------
 */

declare(strict_types=1);

namespace LaravelFlow\Http\Controllers;

define('BEASE_URL', realpath(dirname(__FILE__)));

define('LaravelFlow_Ver', '1.0.0');
//引用适配器核心控制
use LaravelFlow\Service\Control;
//引用工具类
use LaravelFlow\Lib\Unit;

use Illuminate\Http\Request;

class Api
{
	public function  __construct()
	{
		if (unit::getuserinfo() == -1) {
			die('Access Error!');
		}
	}
	/**
	 * LaravelFlow1.0统一接口流程审批接口
	 * @param string $act 调用接口方法
	 * 调用 LaravelFlow\Adaptive\Control 的核心适配器进行API接口的调用
	 */
	public function WfDo(Request $request, $act = 'index', $wf_type = '', $wf_fid = '')
	{
		if ($act == 'start') {
			if (unit::is_post()) {
				$data = $request->input();
				return unit::return_msg(Control::WfCenter($act, $wf_fid, $wf_type, $data));
			} else {
				return Control::WfCenter($act, $wf_fid, $wf_type);
			}
		}
		if ($act == 'endflow' || $act == 'cancelflow') {
			$bill_table =  $request->query('bill_table') ?? '';
			$bill_id =  $request->query('bill_id') ?? '';
			return unit::return_msg(Control::WfCenter($act, '', '', ['bill_table' => $bill_table, 'bill_id' => $bill_id]));
		}
		if ($act == 'do') {
			$wf_op =  $request->query('wf_op') ?? 'check';
			$ssing =  $request->query('ssing') ?? 'sing';
			$submit =  $request->query('submit') ?? 'ok';
			if (unit::is_post()) {
				$post = $request->input();
				return unit::return_msg(Control::WfCenter($act, $wf_fid, $wf_type, ['wf_op' => $wf_op, 'ssing' => $ssing, 'submit' => $submit], $post));
			} else {
				return unit::return_msg(Control::WfCenter($act, $wf_fid, $wf_type, ['wf_op' => $wf_op, 'ssing' => $ssing, 'submit' => $submit]));
			}
		}
		/*用户确认抄送*/
		if ($act == 'entCc') {
			$id =  $request->query('id');
			return Control::WfCenter($act, $id);
		}
	}
	/**
	 * LaravelFlow1.0统一接口设计器
	 * @param string $act 调用接口方法
	 * 调用 LaravelFlow\Adaptive\Control 的核心适配器进行API接口的调用
	 * @return  返回类型
	 */
	public function designapi(Request $request, $act, $flow_id = '')
	{
		if ($act == 'welcome' || $act == 'check' || $act == 'delAll' || $act == 'wfdesc') {
			return unit::return_msg(Control::WfDescCenter($act, $flow_id));
		}
		if ($act == 'add') {
			$data = $request->input('data');
			return unit::return_msg(Control::WfDescCenter($act, $flow_id, $data));
		}
		if ($act == 'save') {
			$data = $request->input('process_info');
			return unit::return_msg(Control::WfDescCenter($act, $flow_id, $data));
		}
		if ($act == 'del') {
			$data = $request->input('id');
			return unit::return_msg(Control::WfDescCenter($act, $flow_id, $data));
		}
		if ($act == 'att') {
			return unit::return_msg(Control::WfDescCenter($act, '', $flow_id));
		}
		if ($act == 'saveatt') {
			$data = $request->input();
			return unit::return_msg(Control::WfDescCenter($act, '', $data));
		}
		if ($act == 'super_user') {
			$type_mode = $request->input('type_mode');
			$key = $request->input('key');
			$type = $request->input('type');
			return unit::return_msg(Control::WfDescCenter($act, '', ['kid' => $flow_id, 'type_mode' => $type_mode, 'key' => $key, 'type' => $type]));
		}
	}
	/**
	 * LaravelFlow1.0统一接口 流程管理
	 * @param string $act 调用接口方法
	 * 调用 LaravelFlow\Adaptive\Control 的核心适配器进行API接口的调用
	 * @return array 返回类型
	 */
	public function wfapi(Request $request, $act = 'index')
	{
		if ($act == 'index' || $act == 'wfjk' || $act == 'verUpdate') {
			return Control::WfFlowCenter($act);
		}
		if ($act == 'wfdl') {
			return Control::WfEntrustCenter('index');
		}
		if ($act == 'event') {
			if (unit::is_post()) {
				$data = $request->input();
				return unit::return_msg(Control::WfFlowCenter($act, $data));
			} else {
				$data = input('id') ?? -1;
				return unit::return_msg(Control::WfFlowCenter($act, $data));
			}
		}
		if ($act == 'add') {
			if (unit::is_post()) {
				$data = $request->input();
				unset($data['_token'], $data['s']);
				return unit::return_msg(Control::WfFlowCenter($act, $data));
			} else {
				$data = $request->query('id') ?? -1;
				return unit::return_msg(Control::WfFlowCenter($act, $data));
			}
		}
		if ($act == 'del') {
			if (unit::is_post()) {
				$data = input('post.');
				return unit::return_msg(Control::WfFlowCenter($act, $data));
			}
		}
		if ($act == 'wfend') {
			$id = $request->input('id');
			return Control::WfFlowCenter($act, $id);
		}
		if ($act == 'dladd') {
			if (unit::is_post()) {
				$data = $request->input();
				unset($data['_token'], $data['s']);
				return Control::WfEntrustCenter('add', $data);
			} else {
				$id = $request->query('id');
				return Control::WfEntrustCenter('add', $id);
			}
		}
	}
	/**
	 * LaravelFlow1.0统一接口 数据接口
	 * @param string $act 调用接口方法
	 * @param int    $uid 用户id
	 * @param array  $map 查询方法
	 * 调用 LaravelFlow\Adaptive\Control 的核心适配器进行API接口的调用
	 * @return array 返回类型
	 */
	public static function wfUserData($act = 'userFlow', $map = [], $field = '', $order = '', $group = '', $page = 1, $limit = 20)
	{
		return Control::wfUserData($act, $map, $field, $order, $group, $page, $limit);
	}


	/**
	 * LaravelFlow1.0统一接口 前端权限控制中心
	 * @param string $act 调用接口方法
	 * @param string $data 调用接口方法
	 * 调用 LaravelFlow\Adaptive\Control 的核心适配器进行API接口的调用
	 * @return array 返回类型
	 */
	public static function wfAccess($act = 'log', $data = '')
	{
		return Control::wfAccess($act, $data);
	}

	public static  function wfMysend($page = 1, $limit = 20)
	{
		return Control::wfMysend($page, $limit);
	}
}
