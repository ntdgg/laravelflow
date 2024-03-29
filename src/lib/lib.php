<?php

/**
 *+------------------
 * LaravelFlow 公共类，模板文件
 *+------------------
 * Copyright (c) 2018~2025 liuzhiyun.com All rights reserved.  本版权不可删除，侵权必究
 *+------------------
 * Author: guoguo(1838188896@qq.com)
 *+------------------
 */

namespace LaravelFlow\Lib;

use LaravelFlow\Adaptive\Cc;
use LaravelFlow\Adaptive\Process;
use LaravelFlow\Adaptive\User;
use LaravelFlow\Adaptive\Bill;

class Lib
{
    /**
     * 工作流状态信息
     *
     * @param  $status 状态
     * @param  $type 0 Html 1 Json
     **/
    public static function LaravelFlow_status($status = 0, $type = 0)
    {
        $stv = [
            -1 => '<span class="label label-danger radius" >退回修改</span>', 0 => '<span class="label radius">保存中</span>', 1 => '<span class="label radius" >流程中</span>', 2 => '<span class="label label-success radius" >审核通过</span>'
        ];
        $st = [
            -1 => '退回修改', 0 => '保存中', 1 => '流程中', 2 => '审核通过'
        ];
        if ($type == 0) {
            return $stv[$status] ?? 'ERR';
        } else {
            return $st[$status] ?? 'ERR';
        }
    }

    /**
     * 工作流按钮权限
     *
     **/
    public static function LaravelFlow_btn($wf_fid, $wf_type, $status, $flowinfo, $return = 0)
    {
        $btn_lang = Unit::gconfig('wf_btn');
        $urls = Unit::gconfig('wf_url');
        $thisuser = ['thisuid' => Unit::getuserinfo('uid'), 'thisrole' => Unit::getuserinfo('role')];
        $url = ['url' => $urls['wfdo'] . '/do/' . $wf_type . '/' . $wf_fid];
        $ccHtml = Cc::ccStatus($wf_type, $wf_fid);
        switch ($status) {
            case 0:
                $start_flow = (array)Unit::gconfig('start_flow'); // Guoke 2021/11/26 16:55 修复空数据下报错
                $btn_access = true;
                if (in_array($wf_type, $start_flow)) {
                    $uid = Bill::getbillvalue($wf_type, $wf_fid, 'uid');
                    if ($uid != Unit::getuserinfo('uid')) {
                        $btn_access = false;
                    }
                }
                if ($return == 1) {
                    return ['Url' => $urls['wfdo'] . '/start/' . $wf_type . '/' . $wf_fid, 'User' => '', 'status' => 1];
                }
                if (!$btn_access) {
                    return '';
                }

                $btnHtml =   '<span class="btn" onclick=LaravelFlow.lopen(\'发起\',"' . $urls['wfdo'] . '/start/' . $wf_type . '/' . $wf_fid . '",35,30)>' . $btn_lang['start'] . '</span>';

                break;
            case 1:
                $st = 0;
                $user_name = '';
                if ($flowinfo != -1) {
                    if (!isset($flowinfo['status'])) {
                        if ($return == 1) {
                            return ['Url' => '', 'User' => '', 'status' => -1];
                        }
                        $btnHtml =   '<span class="btn" onclick=javascript:alert("提示：当前流程故障，请联系管理员重置流程！")>Info:Flow Err</span>';
                    }
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
                } else {
                    if ($return == 1) {
                        return ['Url' => '', 'User' => '', 'status' => 0];
                    }
                    $btnHtml =   '<span class="btn">' . $btn_lang['noaccess'] . '</span>';
                }
                if ($st == 1) {
                    if ($return == 1) {
                        return ['Url' => $url['url'], 'User' => $user_name];
                    }
                    $btnHtml =   '<span class="btn" onclick=LaravelFlow.lopen(\'审核单据信息：' . $wf_fid . '\',"' . $url['url'] . '",100,100)>' . $btn_lang['approve'] . '[' . $user_name . ']</span>';
                } else {
                    if ($return == 1) {
                        return ['Url' => '', 'User' => $user_name, 'status' => 0];
                    }
                    $btnHtml =   '<span class="btn">' . $btn_lang['noaccess'] . '[' . $user_name . ']</span>';
                }
                break;
            case 100:
                if ($return == 1) {
                    return ['Url' => $url['url'] . '&sup=1', 'User' => '', 'status' => 1];
                }
                $btnHtml =  '<span class="btn" onclick=LaravelFlow.lopen(\'审核单据信息：' . $wf_fid . '\',"' . $url['url'] . '&sup=1",100,100)>' . $btn_lang['sapprove'] . '</span>';
                break;
            default:
                $btnHtml = '';
        }
        return $btnHtml . $ccHtml;
    }
    /**
     * 添加流程模板
     *
     **/
    public static function tmp_event($url, $info, $type)
    {
        if (!$info) {
            $info['type'] = '';
        }
        $tmp = self::commontmp('LaravelFlow V1.0 ');
        $patch = Unit::gconfig('static_url');
        $view = <<<php
				{$tmp['head']}
				<link rel="stylesheet" type="text/css" href="{$patch}lib/codemirror/codemirror.css" />
				<link rel="stylesheet" type="text/css" href="{$patch}lib/codemirror/dracula.css" />
				<form action="{$url}" method="post" name="form" id="form" style="padding: 10px;">
				   <table class="table"><tr>
							<tr class='text-c' ><th>单据信息</th><td style='width:330px;text-align: left;'>
							<span class="select-box"><select name="type" id="type" class="select"  datatype="*" >{$type}</select></span>
							</td></tr>
							<tr class='text-c' ><th>执行类型</th><td style='width:330px;text-align: left;'>
							<span class="select-box"><select name="fun" id="act" class="select"  datatype="*" ><option value="">请选择</option><option value="before">before 步骤执行前动作</option>
                                    <option value="after">after 步骤执行后动作</option>
                                    <option value="cancel">cancel 执行取消动作</option>
							</td></tr>
							<th>执行代码</th><td style='width:330px;text-align: left;'>
								<textarea placeholder="" name='code' type="text/plain" style="width:100%;height:450px;display:inline-block;" id='codedata' ></textarea></td>
							</tr><tr class='text-c' >
							<td colspan=2>
							<button  class="button" type="submit">&nbsp;&nbsp;保存&nbsp;&nbsp;</button>&nbsp;&nbsp;<button  class="button" type="button" onclick="LaravelFlow.lclose()">&nbsp;&nbsp;取消&nbsp;&nbsp;</button></td></tr>
						</table>
					</form>{$tmp['js']}{$tmp['form']}
					<script  src="{$patch}lib/codemirror/codemirror.js" ></script>
					<script src="{$patch}lib/codemirror/javascript.js"></script>
			<script type="text/javascript">
			var editor = CodeMirror.fromTextArea(document.getElementById("codedata"), {
        lineNumbers: true,
        lineWrapping: true,
        mode: "text/typescript",
        theme: "dracula",	//设置主题
        matchBrackets: true,
    });
    editor.setSize('auto',document.body.clientHeight - 140 +"px");
      $('#act').on('change',function(){
        var act = $('#act').val();
        $.ajax({
            type:'post',
            dataType:'json',
            data:{type:$('#type').val(),fun:act,info:'1'},
            url:'{$url}',
            success:function(res){
                editor.setValue(res.data);
            }
        });
    });
			</script>
php;
        return $view;
    }
    /**
     * 添加流程模板
     *
     **/
    public static function tmp_add($url, $info, $type)
    {
        if (!$info) {
            $info['id'] = '';
            $info['flow_name'] = '';
            $info['sort_order'] = '';
            $info['flow_desc'] = '';
            $info['type'] = '';
            $info['field_name'] = '';
            $info['field_value'] = '';
            $info['is_field'] = 0;
            $info['tmp'] = '';
        }
        $tmp = self::commontmp('LaravelFlow V1.0 ');
        $csr = csrf_token();
        $view = <<<php
				{$tmp['head']}
				<form action="{$url}" method="post" name="form" id="form" style="padding: 10px;">
				<input type="hidden" name="id" value="{$info['id']}">
				<input type="hidden" name="_token" value="{$csr}">
				   <table class="table"><tr><th style='width:75px'>流程名称</th><td style='width:330px;text-align: left;'>
							<input type="text" class="input-text" value="{$info['flow_name']}" name="flow_name"  datatype="*" ></td></tr><tr>
							<th>流程类型</th><td style='width:330px;text-align: left;'>
							<span class="select-box"><select name="type"  class="smalls"  datatype="*" >{$type}</select></span>
							</td></tr>
							<tr><th style='width:75px'>单据过滤</th>
							<td style='width:330px;text-align: left;'>
							过滤:<select name="is_field"  class="smalls"  datatype="*" ><option value="0">关闭</option><option value="1">开启</option></select><br/>
							字段:<input type="text" class="input-text" value="{$info['field_name']}" name="field_name"><br/>数值:<input type="text" class="input-text" value="{$info['field_value']}" name="field_value"   ></td></tr><tr>
							<tr><th style='width:75px'>业务名称</th>
							<td style='width:330px;text-align: left;'><input type="text" class="input-text" value="{$info['tmp']}" name="tmp" >*【title】</td></tr><tr>
							<tr><th style='width:75px'>排序值</th>
							<td style='width:330px;text-align: left;'><input type="text" class="input-text" value="{$info['sort_order']}" name="sort_order"  datatype="*" ></td></tr><tr>
							<th>流程描述</th><td style='width:330px;text-align: left;'>
								<textarea name='flow_desc'  datatype="*" style="width:100%;height:55px;">{$info['flow_desc']}</textarea></td>
							</tr><tr class='text-c' >
							<td colspan=2>
							<button  class="button" type="submit">&nbsp;&nbsp;保存&nbsp;&nbsp;</button>&nbsp;&nbsp;<button  class="button" type="button" onclick="LaravelFlow.lclose()">&nbsp;&nbsp;取消&nbsp;&nbsp;</button></td></tr>
						</table>
					</form>{$tmp['js']}{$tmp['form']}
			<script type="text/javascript">
			$(function(){
				$("[name='type']").find("[value='{$info['type']}']").attr("selected",true);
				$("[name='is_field']").find("[value='{$info['is_field']}']").attr("selected",true);
			});
			</script>
php;
        return $view;
    }

    /**
     * 添加代理会签模板
     *
     **/
    public static function tmp_entrust($info, $type, $user)
    {
        $tmp = self::commontmp('LaravelFlow V1.0 管理列表');
        $urls = Unit::gconfig('wf_url');
        $csr = csrf_token();
        return <<<php
				{$tmp['head']}
				<form action="{$urls['wfapi']}/dladd" method="post" name="form" id="form">
				<input type="hidden" name="id" value="{$info['id']}">
				<input type="hidden" name="_token" value="{$csr}">
				   <table class="table">
							<tr><th style='width:75px'>委托标题</th>
							<td style='width:330px;text-align: left;'><input type="text" class="input-text" name="entrust_title"  datatype="*" value="{$info['entrust_title']}"></td>
							</tr><tr>
							<th>步骤授权</th><td style='width:330px;text-align: left;'>
							<select name="type"  class="select"  datatype="*" >
								<option value="0@0">不指定全局授权</option>';
								{$type}</select>
							</td></tr>
							<tr>
							<th>授权人</th><td style='width:330px;text-align: left;'>
							<select name="oldinfo"  class="select"  datatype="*" >{$user}</select>
							</td></tr>
							<tr>
							<th>被授权人</th><td style='width:330px;text-align: left;'>
							<select name="userinfo"  class="select"  datatype="*" >{$user}</select>
							</td></tr>
							<tr><th>起止时间</th><td style='width:330px;text-align: left;'>
								<input name='entrust_stime' value="{$info['entrust_stime']}" datatype="*" type="datetime-local"/> ~ <input value="{$info['entrust_etime']}" name='entrust_etime' datatype="*" type="datetime-local"/></td>
							</tr><tr>
							<th>委托备注</th><td style='width:330px;text-align: left;'><textarea name='entrust_con'  datatype="*" style="width:100%;height:55px;">{$info['entrust_con']}</textarea></td></tr>
							<tr class='text-c' >
							<td colspan=2>
							<button  class="button" type="submit">&nbsp;&nbsp;提交&nbsp;&nbsp;</button>&nbsp;&nbsp;<button  class="button" type="button" onclick="LaravelFlow.lclose()">&nbsp;&nbsp;取消&nbsp;&nbsp;</button></td>
							</tr><tr><td style='width:330px;text-align: left;' colspan=2>
								注：</td></tr>
						</table>
					</form>
					{$tmp['js']}
					{$tmp['form']}
			<script type="text/javascript">
			$(function(){
				$("[name='type']").find("[value='{$info['type']}']").attr("selected",true);
				$("[name='userinfo']").find("[value='{$info['userinfo']}']").attr("selected",true);
			});
			</script>
php;
    }

    /**
     * 用户角色选择模板
     *
     **/
    public static function tmp_suser($url, $kid, $user, $type = 'user')
    {

        $tmp = self::commontmp('LaravelFlow V1.0 ');
        $csr = csrf_token();
        return <<<php
		 {$tmp['head']}
<article class="page-container">
<table class="table table-bordered table-bg">
			<tr><td><form method="post"><div class="text-l"><input type="text" id="key" style="width:150px" class="input-text"><a id="search" class="button">查询</a></div></form></td></tr>
			<tr><td><select name="dialog_searchable" id="dialog_searchable" multiple="multiple" style="display:none;">{$user}</select></td></tr><tr><td>
			<button class="btn btn-info" type="button" onclick='call_back()' id="dialog_confirm">确定</button>
			<button class="btn" type="button" id="dialog_close">取消</button></td></tr>
			</table>
</article>
{$tmp['js']}
<script type="text/javascript">
	function call_back(){
			var nameText = [];
            var idText = [];
            if(!$('#dialog_searchable').val()){
               layer.msg('未选择');
				return false;
            }else{
              $('#dialog_searchable option').each(function(){
                if($(this).attr("selected")){
                    nameText.push($(this).text());
                    idText.push($(this).val());
                }
                });
                var name = nameText.join(',');
				var ids = idText.join(',');
            }
		var index = parent.layer.getFrameIndex(window.name);
		parent.layer.msg('设置成功');
		parent.$('#{$kid}_ids').val(ids);
		parent.$('#{$kid}_text').val(name);
		parent.$('#{$kid}_html').html(name);
		parent.layer.close(index);
	}
    $(function(){
          $('#dialog_searchable').multiselect2side({
            selectedPosition: 'right',
            moveOptions: false,
            labelsx: '备选',
            labeldx: '已选',
            autoSort: true
            //,autoSortAvailable: true
        });
        $("#search").on("click",function(){
			var url = "{$url}";
			$.post(url,{"type":'{$type}',"_token":'{$csr}',"key":$('#key').val()},function(data){
				layer.msg(data.msg);
				var userdata = data.data;
				var optionList = [];
            for(var i=0;i<userdata.length;i++){
                optionList.push('<option value="');
                optionList.push(userdata[i].id);
                optionList.push('">');
                optionList.push(userdata[i].username);
                optionList.push('</option>');
            }
            $('#dialog_searchablems2side__sx').html(optionList.join(''));
			},'json');
        });
        $("#dialog_close").on("click",function(){
			var index = parent.layer.getFrameIndex(window.name);
            parent.layer.close(index);
        });
    });
</script>
php;
    }

    /**
     * 工作流监控模板
     *
     **/
    public static function tmp_wfjk($data)
    {
        $tmp = self::commontmp('LaravelFlow V1.0 ');
        $csr = csrf_token();
        return <<<php
		 {$tmp['head']}<meta name="csrf-token" content="{$csr}"><div class="page-container"><table class="table"><thead><tr class="text-c"><th>工作流编号</th><th >工作流类型</th><th >工作流名称</th><th >当前状态</th><th >业务办理人</th><th >接收时间</th><th >操作</th></thead></tr>{$data}</table></div>{$tmp['js']}</body></html>
php;
    }

    public static function tmp_wfstart($info, $flow)
    {
        $urls = Unit::gconfig('wf_url');
        $url = $urls['wfdo'] . '/start/' . $info['wf_type'] . '/' . $info['wf_fid'];
        $tmp = self::commontmp('LaravelFlow V1.0 ');
        $csr = csrf_token();
        return <<<php
		 {$tmp['head']}
		<form action="{$url}" method="post" name="form" id="form">
		<input type='hidden' value="{$info['wf_fid']}" name='wf_fid'>
		<input type="hidden" name="_token" value="{$csr}">
		<table class="table">
			<tr><td>选择工作流：</td><td style="text-align:left"><select name="wf_id"  class="smalls "  datatype="*" ><option value="">请选择工作流</option>{$flow}</select>
			</td></tr><tr>
			<td>审核意见：</td><td style="text-align:left"><input type="text" class="input-text" name="check_con"  datatype="*" >
			</td></tr>
			<tr><td colspan='2' style='text-align:center'><button  class="button" type="submit">&nbsp;&nbsp;保存&nbsp;&nbsp;</button>&nbsp;&nbsp;<button  class="button" style="background-color:#666 !important" type="button" onclick="LaravelFlow.lclose()">&nbsp;&nbsp;取消&nbsp;&nbsp;</button></td></tr>
		</table>
	</form>{$tmp['js']}{$tmp['form']}
</body>
</html>
php;
    }

    /**
     * 工作流提交模板
     *
     **/
    public static function tmp_wfok($info, $flowinfo)
    {
        $sup = $_GET['sup'] ?? '';
        $tmp = self::commontmp('LaravelFlow V1.0 ');
        $csr = csrf_token();
        return <<<php
		 {$tmp['head']}
		<form action="{$info['LaravelFlow_ok']}" method="post" name="form" id="wfform">
		<input id='upload' name='art' value='' type='hidden'>
		<input type="hidden" value="{$flowinfo['wf_mode']}" name="wf_mode" >
		<input type="hidden" value="{$flowinfo['nexid']}" name="npid" >
		<input type="hidden" value="{$flowinfo['run_id']}" name="run_id" id='run_id'>
		<input type="hidden" value="{$sup}" name="sup">
		<input type="hidden" value="{$flowinfo['run_process']}" name="run_process">
		<input type="hidden" value="{$flowinfo['flow_process']}" name="flow_process">
		<input type="hidden" name="_token" value="{$csr}">
		<table class="table table-border table-bordered table-bg" style='width:98%'>
			<thead>
			<tr>
			<th style='width:98%' class='text-c'>单据审批</th>
			</tr>
			<tr>
			</thead>
			<td style='height:80px'>
				<table class="table table-border table-bordered table-bg">
				<tr>
				<td style='width:70px'>审批意见</td>
				<td><textarea name='check_con'  datatype="*" style="width:100%;height:55px;"></textarea> </td>
				</tr>
				<tr><td>下一步骤</td>
				<td style="text-align:left">
					{$flowinfo['npi']}
				</td>
				</tr>
				<tr>
				<td colspan=2 class='text-c'>
						<input id='submit_to_save' name='submit_to_save' value='{$info['wf_submit']}' type='hidden'>
						<button  class="button" type="submit"> 提交同意</button>
						<a class="button" id='backbton' onclick='LaravelFlow.lclose()'>取消</a>
						<a class="button" onclick=LaravelFlow.lopen("Upload","{$info['LaravelFlow_upload']}?id=upload",20,50) style="background-color: #19be6b">附件</a>
				</td>
				</tr>
				</table>
			</td>
			</tr>
		</table>
</form>
</div>
{$tmp['js']}
<script type="text/javascript">
$(function(){
	$("#wfform").Validform({
            tiptype:function(msg,o,cssctl){
				if (o.type == 3){
					layer.msg(msg, {time: 800});
				}
			},
            ajaxPost:true,
            showAllError:true,
            callback:function(ret){
                  if (ret.code == 0) {
						layer.msg(ret.msg,{icon:1,time: 1500},function(){
							window.parent.parent.location.reload(); //关闭所有弹出层
							layer.closeAll();
						});
					} else {
					   layer.alert(ret.msg, {title: "错误信息", icon: 2});
					}
            }
        });
});
</script>
</body>
</html>
php;
    }

    /**
     * 工作流回退模板
     *
     **/
    public static function tmp_wfback($info, $flowinfo)
    {
        $preprocess = Process::GetPreProcessInfo($flowinfo['run_process']);
        $op = '';
        foreach ($preprocess as $k => $v) {
            $op .= '<option value="' . $k . '">' . $v . '</option>';
        }
        $sup = $_GET['sup'] ?? '';
        $tmp = self::commontmp('LaravelFlow V1.0 ');
        $csr = csrf_token();
        return <<<php
		 {$tmp['head']}
		<form action="{$info['LaravelFlow_back']}" method="post" name="form" id="wfform">
		<input type="hidden" value="{$flowinfo['run_id']}" name="run_id" id='run_id'>
		<input type="hidden" value="{$sup}" name="sup">
		<input type="hidden" value="{$flowinfo['run_process']}" name="run_process">
		<input type="hidden" name="_token" value="{$csr}">
		<table class="table table-border table-bordered table-bg" style='width:98%'>
			<thead>
			<tr>
			<th style='width:98%' class='text-c'>单据审批</th>
			</tr>
			<tr>
			</thead>
			<td style='height:80px'>
				<table class="table table-border table-bordered table-bg">
				<tr>
				<td style='width:70px'>回退意见</td>
				<td><textarea name='check_con'  datatype="*" style="width:100%;height:55px;"></textarea> </td>
				</tr>
				<tr><td>回退步骤</td>
				<td style="text-align:left"><select name="wf_backflow" id='backflow'  class="smalls"  datatype="*" onchange='find()'>
					<option value="">请选择回退步骤</option>{$op}</select>
				</td>
				</tr>
				<tr>
				<td colspan=2 class='text-c'>
						<input id='submit_to_save' name='submit_to_save' value='back' type='hidden'>
						<button  class="button" type="submit"> 提交回退</button>
						<a class="button" id='backbton' onclick='LaravelFlow.lclose()'>取消</a>
				</td>
				</tr>
				</table>
			</td>
			</tr>
		</table>
</form>
</div>
{$tmp['js']}
<script type="text/javascript">
$(function(){
	$("#wfform").Validform({
            tiptype:function(msg,o,cssctl){
				if (o.type == 3){
					layer.msg(msg, {time: 800});
				}
			},
            ajaxPost:true,
            showAllError:true,
            callback:function(ret){
                  if (ret.code == 0) {
						layer.msg(ret.msg,{icon:1,time: 1500},function(){
							window.parent.parent.location.reload(); //关闭所有弹出层
							layer.closeAll();
						});
					} else {
					   layer.alert(ret.msg, {title: "错误信息", icon: 2});
					}
            }
        });
});
</script>
</body>
</html>
php;
    }

    /**
     * 工作流设计模板
     *
     **/
    public static function tmp_wfdesc($id, $process_data, $urlApi)
    {
        $tmp = self::commontmp('LaravelFlow V1.0 ');
        $data = json_decode($process_data, 'true');
        $surl = $urlApi;
        $id = $id;
        $x6 = json_encode($data['x6']);
        $csr = csrf_token();
        return <<<str
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>LaravelFlow工作流引擎 V1.0</title>
    <link rel="stylesheet" href="/static/work/app.css">
    <meta name="csrf-token" content="{$csr}">
</head>
<body>
<div class="wrap">
    <div class="header">
        LaravelFlow工作流引擎 V1.0
    </div>
<div class='toolbar'>
        <hr  style="margin: 0 5px;padding-top: 5px;border: none;border-bottom: solid 1px #DDD;clear: both;"/>
        <a onclick='TFAPI.sApi("save")'>♡ 保存</a>
        <a onclick='TFAPI.sApi("delAll")'>✘ 清空</a>
        <a onclick='TFAPI.sApi("Refresh")'>➲ 刷新</a>
        <hr  style="margin: 0 5px;padding-top: 5px;border: none;border-bottom: solid 1px #DDD;clear: both;"/>
        <a onclick='TFAPI.sApi("check")'>♢ 校验</a>
        <a onclick='TFAPI.sApi("Help")'>§ 帮助</a>
        <a onclick='TFAPI.sApi()'>↓ 下载</a>
        <a onclick='TFAPI.sApi("Doc")'>© 版权</a>
        <a onclick='TFAPI.sApi("zoomIn")' style="position: fixed;left: 98%;font-weight: 1000;">+</a>
        <a onclick='TFAPI.sApi("zoomOut")' style="position: fixed;left: 97%;font-weight: 1000;">-</a>
    </div>
    <div class="content">
        <div class="app-stencil" id="sidebar"></div>
        <div class="app-content" id="main"></div>
        <div id="minimap" class="minimap"></div>
    </div>
</div>
<script>
    const LaravelFlow_Id = {$id};
    const LaravelFlow_Server_Url = '{$surl}';
</script>
<script src="/static/work/jquery-1.7.2.min.js"></script>
<script src="/static/work/lib/layer/2.4/layer.js"></script>
<script src="/static/work/LaravelFlow.x6.js"></script>
<script src="/static/work/app.js"></script>
<script src="/static/work/LaravelFlow.node.js"></script>
<script src="/static/work/LaravelFlow.api.js"></script>
<script>
    function resize(){
        const width=document.body.offsetWidth - 120;
        const height=document.body.offsetHeight - 100;
        graph.resize(width, height);
    }
    window.onresize = function (){
        resize()
    }
    resize();
    stencil.load([startNode, flowNode, judgeNode, msg_node,ccNode, linkNode ])
    graph.fromJSON({$x6});
</script>
</body>
</html>
str;
    }

    /**
     * 工作流会签模板
     *
     **/
    public static function tmp_wfsign($info, $flowinfo, $sing)
    {
        $UserDb = User::GetUser();
        $op = '';
        foreach ($UserDb as $k => $v) {
            $op .= '<option value="' . $v['id'] . '">' . $v['username'] . '</option>';
        }
        $sup = $_GET['sup'] ?? '';
        $tmp = self::commontmp('LaravelFlow V1.0 ');
        $csr = csrf_token();
        return <<<php
		 {$tmp['head']}
		<form action="{$info['LaravelFlow_sign']}" method="post" name="form" id="wfform">
		<input type="hidden" value="{$flowinfo['run_id']}" name="run_id" id='run_id'>
		<input type="hidden" value="{$sup}" name="sup">
		<input type="hidden" value="{$flowinfo['run_process']}" name="run_process">
		<input type="hidden" name="_token" value="{$csr}">
		<table class="table table-border table-bordered table-bg" style='width:98%'>
			<thead>
			<tr>
			<th style='width:98%' class='text-c'>单据审批</th>
			</tr>
			<tr>
			</thead>
			<td style='height:80px'>
				<table class="table table-border table-bordered table-bg">
				<tr>
				<td style='width:70px'>会签意见</td>
				<td><textarea name='check_con'  datatype="*" style="width:100%;height:55px;"></textarea> </td>
				</tr>
				<tr><td>会签接收人</td>
				<td style="text-align:left">
				<select name="wf_singflow" id='singflow'  class="smalls"  datatype="*" >
					<option value="">请选择会签人</option>{$op}</select>
				</td>
				</tr>
				<tr>
				<td colspan=2 class='text-c'>
						<input id='submit_to_save' name='submit_to_save' value='{$sing}' type='hidden'>
						<button  class="button" type="submit">会签</button>
						<a class="button" id='backbton' onclick='LaravelFlow.lclose()'>取消</a>
				</td>
				</tr>
				</table>
			</td></tr>
		</table>
</form>
</div>
{$tmp['js']}
<script type="text/javascript">
$(function(){
	$("#wfform").Validform({
            tiptype:function(msg,o,cssctl){
				if (o.type == 3){
					layer.msg(msg, {time: 800});
				}
			},
            ajaxPost:true,
            showAllError:true,
            callback:function(ret){
                  if (ret.code == 0) {
						layer.msg(ret.msg,{icon:1,time: 1500},function(){
							window.parent.parent.location.reload(); //关闭所有弹出层
							layer.closeAll();
						});
					} else {
					   layer.alert(ret.msg, {title: "错误信息", icon: 2});
					}
            }
        });
});
</script>
</body>
</html>
php;
    }

    /**
     * 工作流程模板
     *
     **/
    public static function tmp_wfflow($process_data)
    {
        $tmp = self::commontmp('LaravelFlow V1.0 ');
        return <<<php
		 {$tmp['head']}<body  style="height: 100%; overflow: hidden;margin: 0px; padding: 0px;"><div class="panel layout-panel split-center" style="width:100%; cursor: default;" > <div  style="width:100%; height: 800px;" id="flowdesign_canvas"></div></div></div></body>
</html>
{$tmp['js']}
<script type="text/javascript">
var _this = $('#flowdesign_canvas');
$(function(){
	LaravelFlow.show({$process_data});
});
</script>
php;
    }

    /**
     * 工作流列表模板
     *
     **/
    public static function tmp_index($url, $data, $html)
    {
        $tmp = self::commontmp('LaravelFlow V1.0 ');
        $csr = csrf_token();
        $html = <<<str
		{head}
		<meta name="csrf-token" content="{$csr}">
		<div style='padding: 15px;'>
		<a onclick="LaravelFlow.lopen('添加工作流','{url}',55,60)" class="button ">添加</a> <a onclick="location.reload();" class="button" style="background-color: #FFB800;">刷新</a>
		<table class="table" style="text-align: center">
		    <thead><tr><th style="width: 30px">ID</th><th>流程名称</th><th>流程类型</th><th>添加时间</th><th>状态</th><th>操作</th></thead></tr>{data}
		</table>
		</div>
		{js}
		</body>
		</html>
str;
        return str_ireplace(['{head}', '{url}', '{data}', '{js}'], [$tmp['head'], $url, $data, $tmp['js']], $html);
    }

    /**
     * 工作流管理模板
     *
     **/
    public static function tmp_wfgl($data)
    {
        $tmp = self::commontmp('LaravelFlow V1.0 ');
        $urls = Unit::gconfig('wf_url');
        $csr = csrf_token();
        return <<<php
	{$tmp['head']}
	<meta name="csrf-token" content="{$csr}">
<div class="page-container"><div style='float: left;width:80px'><a onclick="LaravelFlow.lopen('添加委托授权','{$urls['wfapi']}/dladd','75','40')" class="button ">委托代理</a> <hr/><a onclick="location.reload();" class="button ">刷新页面</a></div>
<div style='float: left;width:calc(100% - 80px);'><table class="table" ><thead><tr><th>ID</th><th>授权名称</th> <th>委托类型</th><th>授权关系</th><th>起止时间</th><th>委托备注</th><th>操作</th></tr></thead>{$data}</table></div></div>
{$tmp['js']}</body></html>
php;
    }

    /**
     * 工作流审批模板
     *
     **/
    public static function tmp_check($info, $flowinfo)
    {
        if (strpos($flowinfo['status']['wf_action'], '@') !== false) {
            $urldata = explode("@", $flowinfo['status']['wf_action']);
            $url = url(Unit::gconfig('int_url') . '/' . $urldata[0] . '/' . $urldata[1], ['id' => $info['wf_fid'], $urldata[2] => $urldata[3]]) . ($urldata[4] ?? '');
        } else {
            if (strpos($flowinfo['status']['wf_action'], '/') !== false) {
                $url = url(Unit::gconfig('int_url') . '/' . $flowinfo['status']['wf_action'], ['id' => $info['wf_fid']]);
            } else {
                $url = url(Unit::gconfig('int_url') . '/' . $info['wf_type'] . '/' . $flowinfo['status']['wf_action'], ['id' => $info['wf_fid']]);
            }
        }
        if ($flowinfo['sing_st'] == 0) {
            $html = '<a class="button" onclick=LaravelFlow.lopen("提交工作流","' . $info['LaravelFlow_ok'] . '",45,42) style="background-color: #19be6b">√ 同意</a> ';
            if ($flowinfo['status']['is_back'] != 2) {
                $html .= '<a class="button"  onclick=LaravelFlow.lopen("工作流回退","' . $info['LaravelFlow_back'] . '",45,42) style="background-color: #c9302c;">↺ 驳回</a> ';
            }
            if ($flowinfo['status']['is_sing'] != 2) {
                $html .= '<a class="button"  onclick=LaravelFlow.lopen("工作流会签","' . $info['LaravelFlow_sign'] . '&ssing=sing",45,42) style="background-color: #f37b1d;">⇅ 会签</a>';
            }
        } else {
            $html = '<a class="button" style="background-color: #19be6b" onclick=LaravelFlow.lopen("会签提交","' . $info['LaravelFlow_ok'] . '&submit=sok",45,42)>↷ 会签提交</a> <a class="button" style="background-color: #c9302c;"  onclick=LaravelFlow.lopen("会签回退","' . $info['LaravelFlow_ok'] . '&submit=sback",45,42)>↶ 会签回退</a> <a class="button" style="background-color: #f37b1d;" onclick=LaravelFlow.lopen("工作流会签","' . $info['LaravelFlow_sign'] . '&ssing=ssing",45,42)>⇅ 再会签</a>';
        }
        $html .= ' <a class="button" onclick=LaravelFlow.lopen("审批历史","' . $info['LaravelFlow_log'] . '",50,30)>✤ 审批历史</a> ';
        $tmp = self::commontmp('LaravelFlow V1.0 ');

        return <<<php
{$tmp['head']}
<div class="page-container" style='width:100%;padding: 0px;'>
<div class='LaravelFlowController'>
{$html}
</div>
<div class='LaravelFlowForm' >
	<div class='LaravelFlowHead'>单据信息</div>
	<div style='width:100%;overflow-y:scroll; height:100%;'>
		<iframe src="{$url}" id="iframepage" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" onLoad="LaravelFlow.SetHeight()"></iframe>
	</div>
</div>
{$tmp['js']}
</body>
</html>
php;
    }

    /**
     * 步骤属性模板
     *
     **/
    public static function tmp_wfatt($one, $from, $process_to_list, $table = '')
    {
        $urls = Unit::gconfig('wf_url');
        $wf_action = $one['wf_action'] ?? 'view';
        if ($one['process_type'] != 'node-start') {
            $process_type = '<option value="3">自由选择</option>';
        } else {
            $process_type = '';
        }
        if ($one['wf_mode'] != '1') {
            $wf_mode = 'class="hide"';
        } else {
            $wf_mode = '';
        }
        $process_to_html = '';
        foreach ($process_to_list as $k => $v) {
            if (in_array($v['id'], $one['process_to'])) {
                $process_to_html .= '<tr><td style="width: 50px;">' . $v['process_name'] . $v['id'] . '</td><td><table class="table table-condensed"><tr><td><textarea name="process_in_set_' . $v['id'] . '"  type="text/plain" style="width:100%;height:60px;">' . $v['condition'] . '</textarea>
			Tip:填写必须符合SQL语句规范 详见：AdapteeBill::checkbill Where</td></tr></table></td></tr>';
            }
        }
        $from_html = '';
        foreach ($from as $k => $v) {
            $from_html .= '<option value="' . $k . '">' . $v . '</option>';
        }
        $condition = '<option value="0">单线模式（流程为直线型单一办理模式）</option><option value="1">转出模式（符合执行）</option><option value="2">同步模式（均需办理）</option>';
        if (count($process_to_list) <= 1) {
            $condition = '<option value="0">单线模式（流程为直线型单一办理模式）</option>';
        }

        /*6.0.2增加方法接口*/
        $wf_action_select = '';
        $wf_class = Unit::gconfig('wf_action') ?? '';
        if (class_exists($wf_class)) {
            $wf_action_select = (new $wf_class())->info($table);
        }
        $tmp = self::commontmp('LaravelFlow V1.0 管理列表');
        //return view(BEASE_URL.'/template/att.html',['urls'=>$urls,'one'=>$one,'wf_action'=>$wf_action,'process_type'=>$process_type,'from_html'=>$from_html,'condition'=>$condition,'wf_mode'=>$wf_mode,'process_to_html'=>$process_to_html,'tmp'=>$tmp,'wf_action_select'=>$wf_action_select]);
        $style = '';
        if (($one['process_type'] == 'node-cc') or ($one['process_type'] == 'node-msg')) {
            $style = 'style="display: none"';
        }
        $csr = csrf_token();
        //dump($one);exit;
        return <<<php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>LaravelFlow工作流引擎 V1.0</title>
    <link rel="stylesheet" href="/static/work/app.css">
    <link rel="stylesheet" type="text/css" href="/static/work/workflow.5.0.css?V1.0"/>
</head>
<body>
<div class="wrap">
    <div class="content">
        <form action="{$urls['designapi']}/saveatt" method="post" name="form" id="form" style="width: 100%;padding: 10px;">
            <input type="hidden" name="_token" value="{$csr}">
            <div id="box" style='height: auto;width: auto;'>
                    <input type="hidden" name="flow_id" value="{$one['flow_id']}"/>
                    <input type="hidden" name="process_id" value="{$one['id']}"/>
                    <input type="hidden" name="process_condition" id="process_condition" value='{$one['process_tos']}'>
                    <table class="table">
                        <tr>
                            <th>节点ID</th>
                            <td width="240">{$one['id']}（{$one['process_type']}）</td>
                            <th>步骤名称</th>
                            <td><input type="text" class="smalls" name="process_name" value="{$one['process_name']}">
                            </td>
                        </tr>
                        <tr {$style}>
                            <th>权限管理</th>
                            <td>
                                <select name="is_sing" class="smalls">
                                    <option value="1">允许会签</option>
                                    <option value="2">禁止会签</option>
                                </select>
                                <select name="is_back" class="smalls">
                                    <option value="1">允许回退</option>
                                    <option value="2">不允许</option>
                                </select>
                            </td>
                            <th>调用方法</th>
                            <td>
                                <select class="smalls" name="wf_action_select" style="width: 40%;">
                                    <option value="1">自定义</option>
                                    {$wf_action_select}
                                </select>
                                <input type="text" class="smalls" style="width: 50%;" name="wf_action" value="{$wf_action}"> <a target="_blank" href="//gadmin8.com/index/product.html">?</a>
                            </td>
                        </tr>

                        <tr id="node-process" {$style}>
                            <th>步骤模式</th>
                            <td colspan='3'>
                                <select name="wf_mode" id="wf_mode_id" datatype="*" nullmsg="请选择步骤模式" onchange="LaravelFlow.onchange(this,'wf_mode');" class="smalls">
                                    {$condition}
                                </select>
                            </td>
                        </tr>
                        <tr id='wf_mode_2' {$wf_mode} >
                            <td colspan=4>
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th style="width:30px;">步骤</th>
                                        <th>转出条件设置</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <!--模板-->
                                    {$process_to_html}
                                </table>
                            </td>
                        </tr>

                        <tr id="node-user">
                            <th>办理人员</th>
                            <td colspan='3'>
                                <select class="smalls" name="auto_person" id="auto_person_id" datatype="*" nullmsg="请选择办理人员或者角色！" onchange="LaravelFlow.onchange(this,'auto_person');" style="float: left;">
                                    <option value="">请选择</option>
                                    {$process_type}
                                    <option value="2">协同人员</option>
                                    <option value="4">指定人员</option>
                                    <option value="5">指定角色</option>
                                    <option value="6">事务接受</option>
                                </select>
                                <div style="margin-left: 100px;">
                                    <div class="hide auto_person" id="auto_person_2">
                                        <a class="button" onclick="LaravelFlow.lopen('办理人','{$urls['designapi']}/super_user/auto_xt?type_mode=user','60','95')">指定人员</a>
                                        <input type="hidden" name="auto_xt_ids" id="auto_xt_ids" value="{$one['auto_xt_ids']}">
                                        <input type="hidden" name="auto_xt_text" id="auto_xt_text" value="{$one['auto_xt_text']}">
                                        <span id='auto_xt_html'>{$one['auto_xt_text']}</span>
                                    </div>
                                    <div class="hide auto_person" id="auto_person_3">
                                        <a class="button" onclick="LaravelFlow.lopen('办理人','{$urls['designapi']}/super_user/range_user?type_mode=user','60','95')">自由选择</a>
                                        <input type="hidden" name="range_user_ids" id="range_user_ids" value="{$one['range_user_ids']}" datatype="*" nullmsg="请选择办理人员！">
                                        <input type="hidden" name="range_user_text" id="range_user_text" value="{$one['range_user_text']}">
                                        <span id='range_user_html'>{$one['range_user_text']}</span>
                                    </div>
                                    <div class="hide auto_person" id="auto_person_4">
                                        <a class="button" onclick="LaravelFlow.lopen('办理人','{$urls['designapi']}/super_user/auto_sponsor?type_mode=user','60','95')">指定人员</a>
                                        <input type="hidden" name="auto_sponsor_ids" id="auto_sponsor_ids" value="{$one['auto_sponsor_ids']}">
                                        <input  type="hidden" name="auto_sponsor_text" id="auto_sponsor_text" value="{$one['auto_sponsor_text']}">
                                        <span id='auto_sponsor_html'>{$one['auto_sponsor_text']}</span>
                                    </div>
                                    <div class="hide auto_person" id="auto_person_5">
                                        <a class="button" onclick="LaravelFlow.lopen('指定角色','{$urls['designapi']}/super_user/?type_mode=role','60','95')">指定角色</a>
                                        <input type="hidden" name="auto_role_ids" id="auto_role_ids" value="{$one['auto_role_ids']}">
                                        <span id='auto_role_html'>{$one['auto_role_text']}</span>
                                        <input  type="hidden" name="auto_role_text" id="auto_role_text" value="{$one['auto_role_text']}">
                                    </div>
                                    <div class="hide auto_person" id="auto_person_6">
                                        取业务表<select class="smalls" name='work_text'><option value="">选择字段</option>{$from_html}</select>的
                                            <select class="smalls" name="work_ids">
                                                <option value="1">制单人员</option>
                                            </select>
                                    </div>

                                </div>
                                <hr/>
                                <div class="" style="float: left;">
                                    <!--事务接受，即为当条件满足时，系统自动流转到下一个步骤，无需审核-->
                                    当<<select class="smalls" name='work_auto'><option value="">字段值</option>{$from_html}</select>>
                                    <select class="smalls" name="work_condition">
                                        <option value="=">等于</option>
                                        <option value=">">大于</option>
                                        <option value="<">小于</option>
                                        <option value=">=">大于等于</option>
                                        <option value="<=">小于小于</option>
                                        <option value="<>">不等于</option>
                                    </select>
                                    <input type="text" class="smalls" name="work_val" value="{$one['work_val']}">系统自动审批
                                </div>
                            </td>
                        </tr>
                    </table>
            <table class="table">
                <tr>
                    <td>
                        <button class="button" type="submit">保存</button>
                    </td>
                </tr>
            </table>
            </div>
        </form>



    </div>
</div>
<script src="/static/work/jquery-1.7.2.min.js"></script>
<script src="/static/work/lib/layer/2.4/layer.js"></script>
<script src="/static/work/LaravelFlow.api.js"></script>
<script src="/static/work/workflow.5.0.js"></script>
<script type="text/javascript" src="/static/work/lib/Validform/5.3.2/Validform.min.js" ></script>
<script type="text/javascript">
    var apid = {$one['auto_person']};
    var process_type = "{$one['process_type']}";
    $("#auto_person_{$one['auto_person']}").show();
    $("#range_user_ids").removeAttr("datatype");
    $("#auto_sponsor_ids").removeAttr("datatype");
    $("#auto_role_ids").removeAttr("datatype");

    if (process_type !='node-end') {
        if (apid == 3) {
            $("#range_user_ids").attr({datatype: "*", nullmsg: "请选择办理人员1"});
        }
        if (apid == 4) {
            $("#auto_sponsor_ids").attr({datatype: "*", nullmsg: "请选择办理人员2"});
        }
        if (apid == 5) {
            $("#auto_role_ids").attr({datatype: "*", nullmsg: "请选择办理角色3"});
        }
    }else{
        $('#node-user').hide();
        $('#node-user').hide();
        $('#node-process').hide();
    }
    $("[name='auto_person']").find("[value='{$one['auto_person']}']").attr("selected", true);
    $("[name='is_sing']").find("[value='{$one['is_sing']}']").attr("selected", true);
    $("[name='is_back']").find("[value='{$one['is_back']}']").attr("selected", true);
    $("[name='work_text']").find("[value='{$one['work_text']}']").attr("selected", true);
    $("[name='work_ids']").find("[value='{$one['work_ids']}']").attr("selected", true);
    $("[name='wf_mode']").find("[value='{$one['wf_mode']}']").attr("selected", true);
    $("[name='work_auto']").find("[value='{$one['work_auto']}']").attr("selected", true);
    $("[name='wf_action_select']").find("[value='{$one['wf_action']}']").attr("selected", true);
    $("[name='work_condition']").find("[value='{$one['work_condition']}']").attr("selected", true);
        $(function(){
        $("#form").Validform({
            tiptype:function(msg,o,cssctl){
                if (o.type == 3){
                    layer.msg(msg, {time: 800});
                }
            },
            ajaxPost:true,
            showAllError:true,
            callback:function(ret){
                LaravelFlow.common_return(ret);
            }
        });
    });
</script>
</body>
</html>
php;
    }

    /**
     * 公用模板方法
     *
     **/
    static function commontmp($title)
    {
        $patch = Unit::gconfig('static_url');
        $css = '<link rel="stylesheet" type="text/css" href="' . $patch . 'workflow.5.0.css?V1.0"/>';
        $js = '<script type="text/javascript" src="' . $patch . 'jquery-1.7.2.min.js" ></script>
	<script type="text/javascript" src="' . $patch . 'jsPlumb-1.3.16-all-min.js"></script>
			<script type="text/javascript" src="' . $patch . 'lib/layer/2.4/layer.js" ></script>
			<script type="text/javascript" src="' . $patch . 'workflow.5.0.js?v=11" ></script>
			<script type="text/javascript" src="' . $patch . 'lib/Validform/5.3.2/Validform.min.js" ></script>
			<script type="text/javascript" src="' . $patch . 'jquery-ui-1.9.2-min.js?" ></script>
			<script type="text/javascript" src="' . $patch . 'multiselect2side.js" ></script>
			<script type="text/javascript" src="' . $patch . 'lib/H5upload.js" ></script>';
        $head = '<html><title>' . $title . '</title><head>' . $css . '</head><body style="background-color: white;">';
        $form = '<script type="text/javascript">
			$(function(){
				$("#form").Validform({
						tiptype:function(msg,o,cssctl){
							if (o.type == 3){
								layer.msg(msg, {time: 800});
							}
						},
						ajaxPost:true,
						showAllError:true,
						callback:function(ret){
							LaravelFlow.common_return(ret);
						}
					});
			});
			</script>';
        return ['head' => $head, 'css' => $css, 'js' => $js, 'form' => $form];
    }
}
