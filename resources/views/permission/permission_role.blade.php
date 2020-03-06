@extends('index')
@section('content')
    <style>
        .admin-overall{width: 100%; height: 100%;}
        .admin-nav{height: 50px; background-color: #ffffff; line-height: 50px; font-size: 14px; padding-left: 15px;}
        .admin-table{margin: 15px; padding: 0 15px 15px 15px; background-color: #ffffff; border-radius: 5px; height: calc(100% - 95px);}
        .admin-search{width: 100%; height: 60px; border-bottom: 1px solid #f2f2f2 !important;}
        .admin-search form{float: left; margin-top: 11px;}
        .layui-form-item {margin-bottom: 0px;}
        .layui-form-item .layui-inline {margin-bottom: 0px;}
    </style>
    <div class="admin-overall">
        <div class="admin-nav">
            <span class="layui-breadcrumb">
                <a href="/">主页</a>
                <a href="/permission">角色列表</a>
                <a><cite>分配权限</cite></a>
            </span>
        </div>
        <div class="admin-table">
            <div class="admin-search">
                <form class="layui-form" action="">
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <div class="layui-input-inline" style="width: 200px;">
                                <input type="text" name="name" placeholder="权限名称" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <a class="layui-btn layui-btn-warm" id="screen"><i class="layui-icon">&#xe615;</i>查找</a>
                    </div>
                </form>
            </div>
            <input type="hidden" value="{{$id}}" name="id">
            <table id="list" lay-filter="list" lay-data="{id: 'screen'}"></table>
        </div>
    </div>
    <script type="text/html" id="barDemo">
        <a class="layui-btn layui-btn-xs" lay-event="set">设置权限</a>
    </script>
    <script>
        layui.use('table', function(){
            var table = layui.table;
            var tableIns = table.render({
                elem: '#list'
                ,url: '/permission_role'
                ,height: 'full-270'
                ,page: {
                    layout: ['prev', 'page', 'next', 'count', 'skip']
                    ,groups: 5
                }
                ,limit: 40
                ,where: {id: $("input[name='id']").val()}
                ,cols: [[
                    {field: 'id', title: 'ID', width:100}
                    ,{field: 'name', title: '权限名称', width:500}
                    ,{field: 'permission', title: '权限状态', width:500
                        ,templet: function(d){
                            if(d.permission === 1){
                                return '✔';
                            }else{
                                return '✘';
                            }
                        }
                    }
                    ,{fixed: 'right', title:'操作', toolbar: '#barDemo'}
                ]]
            });
            $("#screen").click(function(){
                tableIns.reload({
                    where: {
                        name: $("input[name='name']").val(),
                        id: $("input[name='id']").val()
                    }
                    ,page: {
                        curr: 1
                    }
                });
            });
            table.on('tool(list)', function(obj){
                var data = obj.data;
                var layEvent = obj.event;
                if(layEvent === 'set'){
                    var index = layer.load(2, {shade: [0.1, '#000000']});
                    $.ajax({
                        type: 'post',
                        url: '/permission_role_do',
                        data: {'_token': '{{csrf_token()}}', 'id': $("input[name='id']").val(), 'permission_id': data.id},
                        dataType: 'json',
                        success: function(data){
                            layer.close(index);
                            layer.msg(data.msg, {icon: 1});
                            if(data.data === 1){
                                obj.update({
                                    permission: '✔'
                                });
                            }else{
                                obj.update({
                                    permission: '✘'
                                });
                            }
                        },
                        error: function(){
                            layer.close(index);
                            layer.msg('网络异常请重试', {icon: 5, anim: 6});
                        }
                    });
                }
            });
        });
    </script>
@endsection