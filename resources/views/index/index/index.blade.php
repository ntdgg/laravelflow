@extends('index.pub.base')
<header class="navbar-wrapper">
    <div class="navbar navbar-fixed-top">
        <div class="container-fluid cl"> <a class="logo navbar-logo f-l mr-10 hidden-xs" href="{:url('index')}">
                LaravelFlow</a> <a class="logo navbar-logo-m f-l mr-10 visible-xs" ></a>
            <span class="logo navbar-slogan f-l mr-10 hidden-xs"> V6.0</span>
            <span class='logo navbar-slogan f-l mr-10 hidden-xs'><b>工作流插件</b>  </span>
            <span class='logo navbar-slogan f-l mr-10 hidden-xs'>开源协议：MIT  </span>
            <span class='logo navbar-slogan f-l mr-10 hidden-xs'>作者：蝈蝈（1838188896） 交流群：532797225</span>
            </nav>
            <nav id="Hui-userbar" class="nav navbar-nav navbar-userbar hidden-xs">
                <ul class="cl"><li>{if condition='session("uid")'}欢迎您：{:session('uname')} {else/}请先模拟登入！{/if}</li>
                    <li><a onClick='login()'>切换账号</a></li>
                </ul>
            </nav>
        </div>
    </div>
</header>
<!--左侧菜单开始-->
<aside class="Hui-aside">
    <div class="menu_dropdown bk_2" >
        <dl>
            <dt><i class="Hui-iconfont"></i> 测试业务<i class="Hui-iconfont menu_dropdown-arrow"></i></dt>
            <dd style="display: block;"><ul>
                    <li><a data-href="{{url('news/index')}}" data-title="测试业务" href="javascript:void(0)">测试业务</a></li>
            </dd>
        </dl>
        <dl>
            <dl>
                <dt><i class="Hui-iconfont"></i> 工作流设计<i class="Hui-iconfont menu_dropdown-arrow"></i></dt>
                <dd style="display: block;"><ul>
                        <li><a data-href="{:url('wf/wfapi')}" data-title="工作流列表" href="javascript:void(0)">工作流列表</a></li>
                        <li><a data-href="{:url('wf/wfapi')}?act=wfjk" data-title="工作流监控" href="javascript:void(0)">工作流监控</a></li>
                        <li><a data-href="{:url('wf/wfapi')}?act=wfdl" data-title="工作流代理" href="javascript:void(0)">工作流代理</a></li>
                </dd>
            </dl>
            <dl>
                <dt><i class="Hui-iconfont"></i> 在线帮助<i class="Hui-iconfont menu_dropdown-arrow"></i></dt>
                <dd style="display: block;"><ul>
                        <li><a data-href="http://www.cojz8.com" data-title="官方博客" href="javascript:void(0)">官方博客</a></li>
                        <li><a data-href="{:url('doc')}" data-title="在线开发文档（精简）" href="javascript:void(0)">在线开发文档（精简）</a></li>
                        <li><a data-href="https://www.kancloud.cn/guowenbin/tpflow/" data-title="看云完整文档" href="javascript:void(0)">看云完整文档</a></li>
                        <li><a data-href="https://gitee.com/ntdgg/tpflow/" data-title="源码下载" href="javascript:void(0)">源码下载</a></li>
                </dd>
            </dl>

    </div>
    </div>
</aside>
<!--左侧菜单结束-->
<div class="dislpayArrow hidden-xs"><a class="pngfix" href="javascript:void(0);" onClick="displaynavbar(this)"></a></div>
<section class="Hui-article-box">
    <div id="Hui-tabNav" class="Hui-tabNav hidden-xs">
        <div class="Hui-tabNav-wp">
            <ul id="min_title_list" class="acrossTab cl">
                <li class="active">
                    <span title="我的桌面" data-href="welcome.html">我的桌面</span><em></em></li>
            </ul>
        </div>
        <div class="Hui-tabNav-more btn-group"><a id="js-tabNav-prev" class="btn radius btn-default size-S" href="javascript:;"><i class="Hui-iconfont">&#xe6d4;</i></a><a id="js-tabNav-next" class="btn radius btn-default size-S" href="javascript:;"><i class="Hui-iconfont">&#xe6d7;</i></a></div>
    </div>
    <div id="iframe_box" class="Hui-article">
        <div class="show_iframe">
            <div style="display:none" class="loading"></div>
            <iframe scrolling="yes" frameborder="0" src="{{url('welcome')}}"></iframe>
        </div>
    </div>
</section>
<script>


    var session ="{:session('uid')}";
    if(session =='' ){
        login();
        layer.open({
            type: 2,
            title: '网站',
            shadeClose: true,
            shade: false,
            maxmin: true, //开启最大化最小化按钮
            area: ['493px', '600px'],
            content: '//cojz8.com/'
        });
    }
    function login(){
        layer.open({
            type: 1,
            skin: 'layui-layer-rim', //加上边框
            area: ['520px', '180px'], //宽高
            content: '<h2>模拟登入:</h2><br><b>　　{volist name="user" id="k"}<span class="btn  radius size-S"><a href="{:url("login",["id"=>$k.id,"user"=>$k.username,"role"=>$k.role])}">{$k.username}</a></span>　{/volist}<b>'
        });
    }
</script>
</body>
</html>
