@extends('index')
@section('content')
    <style>
        .admin-overall{width: 100%; height: 100%;}
        .admin-nav{height: 50px; background-color: #ffffff; line-height: 50px; font-size: 14px; padding-left: 15px;}
        .admin-table{margin: 15px; padding: 0 15px 15px 15px; background-color: #ffffff; border-radius: 5px; height: calc(100% - 95px);}
        .admin-search{width: 100%; height: 60px; border-bottom: 1px solid #f2f2f2 !important;}
        .add{float: right; margin-top: 11px;}
        .admin-search form{float: left; margin-top: 11px;}
        .layui-form-item {margin-bottom: 0px;}
        .layui-form-item .layui-inline {margin-bottom: 0px;}
    </style>
    <div class="admin-overall">
        <div class="admin-nav">
            <span class="layui-breadcrumb">
                <a href="/">主页</a>
                <a><cite>管理员列表</cite></a>
            </span>
        </div>
        <div class="admin-table">
            <div class="admin-search">
                <a href="/admin/create" class="layui-btn add">
                    <i class="layui-icon">&#xe608;</i> 添加管理员
                </a>
                <form class="layui-form" action="">
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <div class="layui-input-inline" style="width: 200px;">
                                <input type="text" name="name" placeholder="姓名" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <div class="layui-input-inline" style="width: 200px;">
                                <input type="text" name="phone" placeholder="手机号" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <a class="layui-btn layui-btn-warm" id="screen"><i class="layui-icon">&#xe615;</i>查找</a>
                    </div>
                </form>
            </div>
            <table id="list" lay-filter="list" lay-data="{id: 'screen'}"></table>
        </div>
    </div>
    <script type="text/html" id="barDemo">
        <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
        <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="password">修改密码</a>
        <a class="layui-btn layui-btn-warm layui-btn-xs" lay-event="admin_log">查看日志</a>
        <a class="layui-btn layui-btn-warm layui-btn-xs" lay-event="admin_account">账户</a>
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
    </script>
    <script>
        layui.use('table', function(){
            var table = layui.table;
            var tableIns = table.render({
                elem: '#list'
                ,url: '/admin'
                ,height: 'full-270'
                ,page: {
                    layout: ['prev', 'page', 'next', 'count', 'skip']
                    ,groups: 5
                }
                ,limit: 20
                ,cols: [[
                    {field: 'id', title: 'ID', width:80, sort: true}
                    ,{field: 'name', title: '姓名', width:150}
                    ,{field: 'phone', title: '手机号', width:150}
                    ,{field: 'status', title: '状态', width: 100, event: 'setSign', style:'cursor: pointer;', templet: function(d){if(d.status === 1){return '启用';}else{return '禁用';}}}
                    ,{field: 'role_name', title: '后台角色', width: 120}
                    ,{field: 'rolename', title: '员工角色', width: 120}
                    ,{field: 'last_ip', title: '最后登录IP', width: 120}
                    ,{field: 'last_time', title: '最后登录时间', width: 180, sort: true}
//                    ,{fixed: 'right', title:'操作', toolbar: '#barDemo'}
                    ,{fixed: 'right', title:'操作', templet: function(d){
                        if(d.id === 1)
                        {return '<a class="layui-btn layui-btn-xs layui-btn-disabled">编辑</a><a class="layui-btn layui-btn-xs layui-btn-disabled">修改密码</a><a class="layui-btn layui-btn-xs layui-btn-disabled">查看日志</a><a class="layui-btn layui-btn-xs layui-btn-disabled">账户</a><a class="layui-btn layui-btn-xs layui-btn-disabled">删除</a>';}
                        else
                        {return '<a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a><a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="password">修改密码</a><a class="layui-btn layui-btn-warm layui-btn-xs" lay-event="admin_log">查看日志</a><a class="layui-btn layui-btn-warm layui-btn-xs" lay-event="admin_account">账户</a><a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>';}}}
                ]]
            });
            $("#screen").click(function(){
                tableIns.reload({
                    where: {
                        name: $("input[name='name']").val()
                        ,phone: $("input[name='phone']").val()
                    }
                    ,page: {
                        curr: 1
                    }
                });
            });
            table.on('tool(list)', function(obj){
                var data = obj.data;
                var layEvent = obj.event;
                var tr = obj.tr;
                if(layEvent === 'del'){
                    layer.confirm('您确定要删除吗?', function(index){
                        $.ajax({
                            type: 'delete',
                            url: '/admin/'+data.id,
                            data: {'_token': '{{csrf_token()}}'},
                            dataType: 'json',
                            success: function(data){
                                layer.close(index);
                                if(data.code === 200){
                                    obj.del();
                                    layer.msg(data.msg, {icon: 1});
                                }else{
                                    layer.msg(data.msg, {icon: 5, anim: 6});
                                }
                            },
                            error: function(){
                                layer.close(index);
                                layer.msg('网络异常请重试', {icon: 5, anim: 6});
                            }
                        });
                    });
                } else if(layEvent === 'admin_log'){
                    window.location.href = "/admin_log/"+data.id;
                } else if(layEvent === 'edit'){
                    window.location.href = "/admin/"+data.id+'/edit';
                } else if(layEvent === 'password'){
                    window.location.href = "/admin_pass/"+data.id;
                } else if(layEvent === 'setSign'){
                    layer.confirm('确定要修改此管理员状态吗?', function(index){
                        $.ajax({
                            type: 'post',
                            url: '/admin_status/'+data.id,
                            data: {'_token': '{{csrf_token()}}'},
                            dataType: 'json',
                            success: function(data){
                                if(data.code === 200){
                                    obj.update({
                                        status: data.msg
                                    });
                                    layer.msg('已被'+data.msg, {icon: 1});
                                }else{
                                    layer.msg(data.msg, {icon: 5, anim: 6});
                                }
                            },
                            error: function(){
                                layer.close(index);
                                layer.msg('网络异常请重试', {icon: 5, anim: 6});
                            }
                        });
                    });
                } else if(layEvent === 'admin_account'){
                    window.location.href = "/admin_account/"+data.id;
                }
            });
        });
    </script>
@endsection