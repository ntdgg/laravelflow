@extends('index.pub.base')
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i>  Tpflow 工作流插件示例 </nav>
<div class="page-container">
	<div class="cl pd-5 bg-1 bk-gray">
	<span class="l">
	<a class="btn btn-primary radius" onclick="layer_show('新增新闻','{{url('news/add')}}','850','500')" >
	<i class="Hui-iconfont-add Hui-iconfont"></i> 新增</a>
	<a class="btn btn-secondary radius" onclick='end_flow()'><i class="Hui-iconfont Hui-iconfont-power"></i> 终止</a>
	<a class="btn btn-warning radius" onclick='cancel_flow()'><i class="Hui-iconfont Hui-iconfont-close2"></i> 去审</a>
	</span>

	</div>
	<table class="table table-border table-bordered table-bg">
		<thead>
			<tr class="text-c">
				<th width="25"></th>
				<th width="25">ID</th>
				<th width="50">发布人</th>
				<th width="80">新闻类型</th>
				<th width="150">新闻标题</th>
				<th width="150">发布时间</th>
				<th width="150">状态</th>
				<th  width="150">操作</th>
			</tr>
		</thead>
		<tbody>
            @foreach($list as $k=>$v)
			<tr class="text-c">
				<td><input type="checkbox" value="{{$v->id}}/{{$v->status}}}" name="ids"></td>
				<td>{{$v->id}}</td>
				<td>{{$v->uid}}</td>
				<td>{{$v->new_type}}</td>
				<td>
                    @if($v->new_top==1)
				<i class="Hui-iconfont" style='color:red'>&#xe684;</i>
                    @endif
                        {{$v->new_title}}</td>
				<td>{{date('Y-m-d',$v->add_time)}}</td>
				<td>
				<!--获取流状态-->
				{:\\tpflow\\Api::wfaccess('status',['status'=>$k.status]);}
				</td>
				<td class="td-manage">
				<div class="btn-group">
					<span class="btn  radius size-S" data-title="查看" data-href="{{url('news/view',['id'=>$v->id])}}" onclick="Hui_admin_tab(this)"><i class="Hui-iconfont">查看</span>
						<!--按钮权限验证，审批权限验证，发起验证-->
						{:\\tpflow\\Api::wfaccess('btn',['id'=>$k.id,'type'=>'news','status'=>$k.status]);}
					<span class="btn  radius size-S" onclick="layer_show('修改','{{url('news/edit',['id'=>$v->id])}}','850','500')">修改</span>
				</div>
				</td>
			</tr>
            @endforeach
		</tbody>
	</table>
</div>
<script type="text/javascript" src="__Flow__/workflow.5.0.js" ></script>
<div class="page-bootstrap">{{$list->links()}}</div>
<script type="text/javascript">
 $(document).ready(function (){
         $('input[type=checkbox]').click(function(){
            if ($("input[name='ids']:checked").length > 1) {
				 layer.msg('工作流终止只能选择一个哟~~');
                 $(this).removeAttr("checked");
           }
        });
   });
function end_flow(){
	var val=$("input:checkbox:checked").val();
	if(val==''||val==undefined){
		layer.msg('请选择您要操作的单据~~');return;
	}
	var arr = val.split('/');
	var id = arr[0];
	var status = arr[1];
	if(status==1){
	  var index = layer.load(1, {
		  shade: [0.1,'#fff'] //0.1透明度的白色背景
		});
	  $.ajax({
		 type: "GET",
		 url: "{:url('/index/wf/wfdo')}?act=endflow&bill_id="+id+'&bill_table=news',
		 dataType: "json",
		 success: function(data){
			if(data.code==1){
				layer.close(index);
				layer.alert(data.msg);
			}else{
				window.location.reload();
			}
		  }
	 });
	}else{
		layer.msg('选中的单据已经被审批或者未发起工作流~~');return;
	}
}
function cancel_flow(){
	var val=$("input:checkbox:checked").val();
	if(val==''||val==undefined){
		layer.msg('请选择您要操作的单据~~');return;
	}
	var arr = val.split('/');
	var id = arr[0];
	var status = arr[1];
	if(status==2){
		var index = layer.load(1, {
		  shade: [0.1,'#fff'] //0.1透明度的白色背景
			});
		  $.ajax({
			 type: "GET",
			 url: "{:url('/index/wf/wfdo')}?act=cancelflow&bill_id="+id+'&bill_table=news',
			 dataType: "json",
			 success: function(data){
				if(data.code==1){
					layer.close(index);
					layer.alert(data.msg);
				}else{
					layer.msg(data.msg,{icon:1,time: 1500},function(){
							window.location.reload();
					});
				}
			  }
		 });
	}else{
		layer.msg('选中的单据已经被审批或者未发起工作流~~');return;
	}
}
</script>
</body>
</html>
