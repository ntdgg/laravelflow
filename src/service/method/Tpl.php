<?php

/**
 *+------------------
 * LaravelFlow 核心控制器
 *+------------------
 * Copyright (c) 2018~2025 liuzhiyun.com All rights reserved.  本版权不可删除，侵权必究
 *+------------------
 * Author: guoguo(1838188896@qq.com)
 *+------------------
 */

declare(strict_types=1);

namespace LaravelFlow\Service\Method;

use LaravelFlow\Adaptive\Cc;
use LaravelFlow\Adaptive\Event;
use LaravelFlow\Lib\Unit;
use LaravelFlow\Lib\Lib;

use LaravelFlow\Adaptive\Info;
use LaravelFlow\Adaptive\Flow;
use LaravelFlow\Adaptive\Process;
use LaravelFlow\Adaptive\Run;
use LaravelFlow\Adaptive\Log;
use LaravelFlow\Adaptive\Entrust;
use LaravelFlow\Adaptive\User;
use LaravelFlow\Adaptive\Bill;

use LaravelFlow\Service\TaskService;


class Tpl
{
    /**
     * 工作流程统一接口
     *
     * @param string $act 调用接口方法
     * 调用 LaravelFlow\Adaptive\Control 的核心适配器进行API接口的调用
     * Info    获取流程信息
     * start   发起审批流
     * endflow 审批流终止
     *
     */
    function WfCenter($act, $wf_fid = '', $wf_type = '', $data = '', $post = '')
    {
        //获取流程信息
        if ($act == 'Info') {
            if ($wf_fid == '' || $wf_type == '') {
                return ['msg' => '单据编号，单据表不可为空！', 'code' => '-1'];
            }
            if ($data == 2) {
                return '';
            }
            $sup = $_GET['sup'] ?? '';
            $userinfo = ['uid' => Unit::getuserinfo('uid'), 'role' => Unit::getuserinfo('role')];
            return Info::workflowInfo($wf_fid, $wf_type, $userinfo, $sup);
        }
        //流程发起
        if ($act == 'start') {
            if ($data != '') {
                $flow = (new TaskService())->StartTask($data['wf_id'], $data['wf_fid'], $data['check_con'], Unit::getuserinfo('uid'));
                if ($flow['code'] == 1) {
                    return Unit::msg_return('Success!');
                } else {
                    return Unit::msg_return($flow['msg'], 1);
                }
            }
            $flow = Flow::getWorkflowByType($wf_type);
            //20210508 新增权限过滤
            foreach ($flow as $k => $v) {
                if ($v['is_field'] == 1) {
                    $field_value = Bill::getbillvalue($wf_type, $wf_fid, $v['field_name']);
                    if ($field_value != $v['field_value']) {
                        unset($flow[$k]);
                    }
                }
            }
            $op = '';
            foreach ($flow as $k => $v) {
                $op .= '<option value="' . $v['id'] . '">' . $v['flow_name'] . '</option>';
            }
            return Lib::tmp_wfstart(['wf_type' => $wf_type, 'wf_fid' => $wf_fid], $op);
        }
        if ($act == 'entCc') {
            return Cc::ccCheck($wf_fid);
        }
        //流程审批
        if ($act == 'do') {
            $urls = Unit::gconfig('wf_url');
            $sup = $_GET['sup'] ?? '';
            $wf_op = $data['wf_op'];
            $info = [
                'wf_fid' => $wf_fid,
                'wf_type' => $wf_type,
                'wf_submit' => $data['submit'],
                'LaravelFlow_ok' => $urls['wfdo'] . '/do/' . $wf_type . '/' . $wf_fid . '?wf_op=ok&sup=' . $sup,
                'LaravelFlow_back' => $urls['wfdo'] . '/do/' . $wf_type . '/' . $wf_fid . '?wf_op=back&sup=' . $sup,
                'LaravelFlow_sign' => $urls['wfdo'] . '/do/' . $wf_type . '/' . $wf_fid . '?wf_op=sign&sup=' . $sup,
                'LaravelFlow_flow' => $urls['wfdo'] . '/do/' . $wf_type . '/' . $wf_fid . '?wf_op=flow&sup=' . $sup,
                'LaravelFlow_log' => $urls['wfdo'] . '/do/' . $wf_type . '/' . $wf_fid . '?wf_op=log&sup=' . $sup,
                'LaravelFlow_upload' => Unit::gconfig('wf_upload_file')
            ];
            if ($wf_op == 'check') {
                return Lib::tmp_check($info, self::WfCenter('Info', $wf_fid, $wf_type));
            }
            /*对审批提交执行人进行权限校验*/
            if ($wf_op == 'ok' || $wf_op == 'back' || $wf_op == 'sign') {
                $flowinfo = self::WfCenter('Info', $wf_fid, $wf_type);
                $thisuser = ['thisuid' => Unit::getuserinfo('uid'), 'thisrole' => Unit::getuserinfo('role')];
                $st = 0;
                if ($flowinfo != -1) {
                    if ($flowinfo['sing_st'] == 0) {
                        $user = explode(",", $flowinfo['status']['sponsor_ids']);
                        $user_name = $flowinfo['status']['sponsor_text'];
                        if ($flowinfo['status']['auto_person'] == 2 || $flowinfo['status']['auto_person'] == 3 || $flowinfo['status']['auto_person'] == 4 || $flowinfo['status']['auto_person'] == 6) {
                            if (in_array($thisuser['thisuid'], $user)) {
                                $st = 1;
                            }
                        }
                        if ($flowinfo['status']['auto_person'] == 5) {
                            if (!empty(array_intersect((array)$thisuser['thisrole'], $user))) { // Guoke 2021/11/26 13:30 扩展多多用户组的支持
                                $st = 1;
                            }
                        }
                    } else {
                        if ($flowinfo['sing_info']['uid'] == $thisuser['thisuid']) {
                            $st = 1;
                        } else {
                            $user_name = $flowinfo['sing_info']['uid'];
                        }
                    }
                }
                if ($post != '' && $st == 0) {
                    return Unit::msg_return('对不起，您没有权限审核！', 1);
                }
                if ($st == 0) {
                    return '<script>var index = parent.layer.getFrameIndex(window.name);parent.layer.msg("对不起，您没有审核权限!");setTimeout("parent.layer.close(index)",1000);</script>';
                }
            }

            if ($wf_op == 'ok') {
                if ($post != '') {
                    $flowinfo = (new TaskService())->Runing($post, Unit::getuserinfo('uid'));
                    if ($flowinfo['code'] == '0') {
                        return Unit::msg_return('Success!');
                    } else {
                        return Unit::msg_return($flowinfo['msg'], 1);
                    }
                }
                return Lib::tmp_wfok($info, self::WfCenter('Info', $wf_fid, $wf_type));
            }
            if ($wf_op == 'back') {
                if ($post != '') {
                    $post['btodo'] = Run::getprocessinfo($post['wf_backflow'], $post['run_id']);
                    $flowinfo = (new TaskService())->Runing($post, Unit::getuserinfo('uid'));
                    if ($flowinfo['code'] == '0') {
                        return Unit::msg_return('Success!');
                    } else {
                        return Unit::msg_return($flowinfo['msg'], 1);
                    }
                }
                return Lib::tmp_wfback($info, self::WfCenter('Info', $wf_fid, $wf_type));
            }
            if ($wf_op == 'sign') {
                if ($post != '') {
                    $flowinfo = (new TaskService())->Runing($post, Unit::getuserinfo('uid'));
                    if ($flowinfo['code'] == '0') {
                        return Unit::msg_return('Success!');
                    } else {
                        return Unit::msg_return($flowinfo['msg'], 1);
                    }
                }
                return Lib::tmp_wfsign($info, self::WfCenter('Info', $wf_fid, $wf_type), $data['ssing']);
            }
            //调用当前审批流的审批流程图
            if ($wf_op == 'flow') {
                $flowinfo = self::WfCenter('Info', $wf_fid, $wf_type);
                $run_info = Run::FindRunId($flowinfo['run_id']);
                $flow_id = intval($run_info['flow_id']);
                if ($flow_id <= 0) {
                    return Unit::msg_return('参数有误，请返回重试!', 1);
                }
                $one = Flow::getWorkflow($flow_id);
                if (!$one) {
                    return Unit::msg_return('参数有误，请返回重试!', 1);
                }
                return Lib::tmp_wfflow(Flow::ProcessAll($flow_id));
            }
            if ($wf_op == 'log') {
                return Log::FlowLog($wf_fid, $wf_type);
            }
        }
        //超级接口
        if ($act == 'endflow') {
            $data = (new TaskService())->EndTask(Unit::getuserinfo('uid'), $data['bill_table'], $data['bill_id']);
            if ($data['code'] == '-1') {
                return Unit::msg_return($data['msg'], 1);
            }
            return Unit::msg_return('Success!');
        }
        if ($act == 'cancelflow') {
            if (is_object(Unit::LoadClass($data['bill_table'], $data['bill_id']))) {
                $BillWork = (Unit::LoadClass($data['bill_table'], $data['bill_id']))->cancel();

                if ($BillWork['code'] == -1) {
                    return $BillWork;
                }
            }
            $bill_update = Bill::updatebill($data['bill_table'], $data['bill_id'], 0);
            if (!$bill_update) {
                return Unit::msg_return($data['msg'], 1);
            }
            return Unit::msg_return('Success!');
        }
        return $act . '参数出错';
    }

    /**
     * LaravelFlow1.0统一接口 流程管理中心
     * @param string $act 调用接口方法
     * 调用 LaravelFlow\Adaptive\Control 的核心适配器进行API接口的调用
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
    function WfFlowCenter($act, $data = '')
    {
        $urls = Unit::gconfig('wf_url');
        if ($act == 'index') {
            $type = [];
            foreach (Info::get_wftype() as $k => $v) {
                $type[$v['name']] = str_replace('[work]', '', $v['title']);;
            }
            $html = '';
            foreach ($type as $k => $v) {
                $html .= '<li>┣' . $k . '-' . $v . '</li>';
            }
            $data = Flow::GetFlow();
            $tr = '';
            foreach ($data['rows'] as $k => $v) {
                $status = ['正常', '禁用'];
                if ($v['edit'] == '') {
                    $url_edit = $urls['wfapi'] . '/add?id=' . $v['id'];
                    $url_desc = $urls['designapi'] . '/wfdesc/' . $v['id'];
                    $btn = "<a class='button' onclick=LaravelFlow.lopen('修改','" . $url_edit . "','55','60')> 修改</a> <a class='button' onclick=LaravelFlow.lopen('设计','" . $url_desc . "',100,100)> 设计</a> ";
                } else {
                    $btn = "<a class='btn  radius size-S'> 运行中....</a>";
                }
                if ($v['status'] == 0) {
                    $btn .= "<a class='button' onclick=LaravelFlow.wfconfirm('" . $urls['wfapi'] . '/add' . "',{'id':" . $v['id'] . ",'status':1},'您确定要禁用该工作流吗？')> 禁用</a>";
                } else {
                    $btn .= "<a class='button' onclick=LaravelFlow.wfconfirm('" . $urls['wfapi'] . '/add' . "',{'id':" . $v['id'] . ",'status':0},'您确定要启用该工作流吗？')> 启用</a>";
                }
                $tr .= '<tr><td>' . $v['id'] . '</td><td>' . $v['flow_name'] . '</td><td>' . ($type[$v['type']] ?? 'Err') . '</td><td>' . date('Y/m/d H:i', $v['add_time']) . '</td><td>' . $status[$v['status']] . '</td><td>' . $btn . '</td></tr>';
            }
            return Lib::tmp_index($urls['wfapi'] . '/add', $tr, $html);
        }
        if ($act == 'wfjk') {
            $data = Info::worklist();
            $html = '';
            foreach ($data as $k => $v) {
                $status = ['未审核', '已审核'];

                $html .= '<tr class="text-c"><td>' . $v['id'] . '</td><td>' . $v['from_table'] . '</td><td>' . $v['flow_name'] . '</td><td>' . $status[$v['status']] . '</td><td>' . $v['flow_name'] . '</td><td>' . date("Y-m-d H:i", $v['dateline']) . '</td><td><a  onclick=LaravelFlow.wfconfirm("' . $urls['wfapi'] . '/wfend",{"id":' . $v['id'] . '},"您确定要终止该工作流吗？");>终止</a>  |  ' . Lib::LaravelFlow_btn($v['from_id'], $v['from_table'], 100, self::WfCenter('Info', $v['from_id'], $v['from_table'])) . '</td></tr>';
            }
            return Lib::tmp_wfjk($html);
        }
        if ($act == 'wfend') {
            return (new TaskService())->doSupEnd($data, Unit::getuserinfo('uid'));
        }
        if ($act == 'add') {
            if ($data != '' && !is_numeric($data)) {
                if ($data['id'] == '') {
                    $data['uid'] = Unit::getuserinfo('uid');
                    $data['add_time'] = time();
                    unset($data['id']);
                    $ret = Flow::AddFlow($data);
                } else {
                    $ret = Flow::EditFlow($data);
                }
                if ($ret['code'] == 0) {
                    return Unit::msg_return('操作成功！');
                } else {
                    return Unit::msg_return($ret['data'], 1);
                }
            }
            $info = Flow::getWorkflow($data); //获取工作流详情
            $type = '';
            foreach (Info::get_wftype() as $k => $v) {
                $type .= '<option value="' . $v['name'] . '">' . $v['title'] . '</option>';
            }
            return Lib::tmp_add($urls['wfapi'] . '/add', $info, $type);
        }
        if ($act == 'event') {
            if ($data != '' && !is_numeric($data)) {
                if (isset($data['info'])) {
                    return Event::getFun($data['fun'], $data['type']);
                }
                if (isset($data['code'])) {
                    $ret =  Event::save($data);
                    if ($ret['code'] == 0) {
                        return Unit::msg_return('操作成功！');
                    } else {
                        return Unit::msg_return($ret['data'], 1);
                    }
                }
            }
            $info = Flow::getWorkflow($data); //获取工作流详情
            $type = '';
            foreach (Info::get_wftype() as $k => $v) {
                $type .= '<option value="' . $v['name'] . '">' . $v['title'] . '</option>';
            }
            return Lib::tmp_event($urls['wfapi'] . '?act=event', $info, $type);
        }
        if ($act == 'del') {
            if ($data != '' && !is_numeric($data)) {
                //判断当前是否有运行流程
                $ret = Run::FindRun(['flow_id' => $data['id'], 'status' => 0], 'status');
                if ($ret && $ret['status'] == 0) {
                    return Unit::msg_return('流程运行中，无法删除!', 1);
                }
                $del_flow = Flow::del($data['id']);
                if (!$del_flow) {
                    return Unit::msg_return('删除流程信息失败！!', 1);
                }
                $find_pro = Process::SearchFlowProcess(['flow_id' => $data['id']]);
                if (count($find_pro) > 0) {
                    //删除步骤
                    $del_pro = Process::DelFlowProcess(['flow_id' => $data['id']]);
                    if (!$del_pro) {
                        return Unit::msg_return('删除步骤信息失败，请手动删除！!', 1);
                    }
                }
                return Unit::msg_return('操作成功！');
            }
        }
        if ($act == 'verUpdate') {
            Flow::verUpdate();
            return json(Unit::msg_return('版本更新成功！'));
        }
        return $act . '参数出错';
    }

    /**
     * LaravelFlow1.0 工作流代理接口
     * @param string $act 调用接口方法
     * 调用 LaravelFlow\Adaptive\Control 的核心适配器进行API接口的调用
     * index 列表调用
     * add   添加代理授权
     */
    function WfEntrustCenter($act, $data = '')
    {
        $urls = Unit::gconfig('wf_url');
        if ($act == 'index') {
            $list = Entrust::lists();
            $html = '';
            foreach ($list as $k => $v) {
                $btn = "<a class='button' onclick=LaravelFlow.lopen('修改','" . $urls['wfapi'] . '/dladd?id=' . $v['id'] . "','65','60')> 修改</a> ";
                $sq = "步骤授权";
                if ($v['flow_id'] == 0) {
                    $sq = "全局授权";
                }
                $html .= '<tr><td>' . $v['id'] . '</td><td>' . $v['entrust_title'] . '</td><td>' . $sq . '</td><td>' . $v['old_name'] . '=>' . $v['entrust_name'] . '</td><td>' . date('Y/m/d H:i', $v['entrust_stime']) . '~' . date('Y/m/d H:i', $v['entrust_etime']) . '</td><td>' . $v['entrust_con'] . '</td><td>' . $btn . '</td></tr>';
            }
            return Lib::tmp_wfgl($html);
        }
        if ($act == 'add') {
            if ($data != '' && !is_numeric($data)) {
                $ret = Entrust::Add($data);
                if ($ret['code'] == 0) {
                    return Unit::msg_return('发布成功！');
                } else {
                    return Unit::msg_return($ret['data'], 1);
                }
            }
            $info = Entrust::find($data);
            //获取全部跟自己相关的步骤
            $data = Process::get_userprocess(Unit::getuserinfo('uid'), Unit::getuserinfo('role'));
            $type = '';
            foreach ($data as $k => $v) {
                $type .= '<option value="' . $v['id'] . '@' . $v['flow_id'] . '">[' . $v['flow_name'] . ']' . $v['process_name'] . '</option>';
            }
            $user = User::GetUser();
            $user_html = '';
            foreach ($user as $k2 => $v2) {
                $user_html .= '<option value="' . $v2['id'] . '@' . $v2['username'] . '">' . $v2['username'] . '</option>';
            }
            return Lib::tmp_entrust($info, $type, $user_html);
        }
        return $act . '参数出错';
    }

    /**
     * LaravelFlow1.0统一接口设计器
     * @param string $act 调用接口方法
     * 调用 LaravelFlow\Adaptive\Control 的核心适配器进行API接口的调用
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
    function WfDescCenter($act, $flow_id = '', $data = '')
    {
        $urls = Unit::gconfig('wf_url');
        //流程添加，编辑，查看，删除
        if ($act == 'welcome') {
            return '<br/><br/><style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; }h1{ font-size: 40px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 26px }</style><div style="padding: 24px 48px;"> <h1>\﻿ (•◡•) / </h1><p> LaravelFlow V1.0正式版<br/><span style="font-size:16px;">PHP优秀的开源工作流引擎</span></p><span style="font-size:13px;">[ ©2018-2022 Guoguo <a href="https://www.cojz8.com/">LaravelFlow</a>  ]</span></div>';
        }
        if ($act == 'wfdesc') {
            $one = Flow::getWorkflow($flow_id);
            if (!$one) {
                return '未找到数据，请返回重试!';
            }
            return Lib::tmp_wfdesc($one['id'], Flow::ProcessAll($flow_id), $urls['designapi']);
        }
        if ($act == 'save') {
            return Flow::ProcessLink($flow_id, $data);
        }
        if ($act == 'check') {
            return Flow::CheckFlow($flow_id);
        }
        if ($act == 'add') {
            $one = Flow::getWorkflow($flow_id);
            if (!$one) {
                return ['status' => 0, 'msg' => '添加失败,未找到流程', 'info' => ''];
            }
            return Flow::ProcessAdd($flow_id, $data);
        }
        if ($act == 'delAll') {
            return Flow::ProcessDelAll($flow_id);
        }
        if ($act == 'del') {
            return Flow::ProcessDel($flow_id, $data);
        }
        if ($act == 'saveatt') {
            return Flow::ProcessAttSave($data);
        }
        if ($act == 'att') {
            $info = Flow::ProcessAttView($data);
            $one = Flow::getWorkflow($info['info']['flow_id']);
            return Lib::tmp_wfatt($info['info'], $info['from'], $info['process_to_list'], $one['type']);
        }
        if ($act == 'super_user') {
            if ($data['type_mode'] == 'user') {
                $info = User::GetUser();
                $user = '';
                foreach ($info as $k => $v) {
                    $user .= '<option value="' . $v['id'] . '">' . $v['username'] . '</option>';
                }
                return Lib::tmp_suser($urls['designapi'] . '/super_user?type_mode=super_get', $data['kid'], $user);
            } elseif ($data['type_mode'] == 'role') {
                $info = User::GetRole();
                $user = '';
                foreach ($info as $k => $v) {
                    $user .= '<option value="' . $v['id'] . '">' . $v['username'] . '</option>';
                }
                return Lib::tmp_suser($urls['designapi'] . '/super_user?type_mode=super_get', 'auto_role', $user, 'role');
            } else {
                return ['data' => User::AjaxGet(trim($data['type']), $data['key']), 'code' => 1, 'msg' => '查询成功！'];
            }
        }
        return $act . '参数出错';
    }

    /**
     * LaravelFlow1.0统一接口
     * @param string $act 调用接口方法
     * 调用 LaravelFlow\Adaptive\Control 的核心适配器进行API接口的调用
     * log  历史日志消息
     * btn  权限判断
     * status  状态判断
     */
    function wfAccess($act, $data = '')
    {
        if ($act == 'log') {
            echo Log::FlowLog($data['id'], $data['type']);
            exit;
        }
        if ($act == 'btn') {
            $info = [];
            if ($data['status'] == 1) {
                $info = self::WfCenter('Info', $data['id'], $data['type'], $data['status']);
            }
            return (new lib())::LaravelFlow_btn($data['id'], $data['type'], $data['status'], $info);
        }
        if ($act == 'status') {
            return (new lib())::LaravelFlow_status($data['status']);
        }
        return $act . '参数出错';
    }

    /**
     * LaravelFlow1.0统一接口
     * @param string $act 调用接口方法
     * 调用 LaravelFlow\Adaptive\Control 的核心适配器进行API接口的调用
     * userFlow  用户流程数据
     * userData  用户数据分组查询
     */
    function wfUserData($act, $map, $field, $order, $group, $page, $limit)
    {
        if ($act == 'userData') {
            $data = Run::dataRunProcess($map, $field, $order, $group);
        }
        if ($act == 'userFlow') {
            // Guoke 2021/11/26 15:40 扩展多用户组的支持
            $roles = Unit::getuserinfo('role');
            $tmpRaw = $p = '';
            foreach ((array)$roles as $v) {
                $tmpRaw .= "$p FIND_IN_SET('$v',f.sponsor_ids)";
                $p = ' or';
            }
            $mapRaw = '(f.auto_person != 5 and FIND_IN_SET(' . Unit::getuserinfo('uid') . ",f.sponsor_ids)) or (f.auto_person=5 and ($tmpRaw))";
            $data = Run::dataRunProcess($map, $mapRaw, $field, $order, $group, $page, $limit);
        }
        return ['code' => 1, 'msg' => '查询成功', 'data' => $data];
    }

    function wfMysend($page, $limit)
    {
        $data = Run::dataRunMy(Unit::getuserinfo('uid'), $page, $limit);
        return ['code' => 1, 'msg' => '查询成功', 'data' => $data['data'], 'count' => $data['count']];
    }
}
