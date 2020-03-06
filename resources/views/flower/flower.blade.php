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
                <a><cite>花束列表</cite></a>
            </span>
        </div>
        <div class="admin-table">
            <div class="admin-search">
                <a href="/flower/create" class="layui-btn add">
                    <i class="layui-icon">&#xe608;</i> 添加花束
                </a>
                <form class="layui-form" action="">
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <div class="layui-input-inline" style="width: 200px;">
                                <input type="text" name="flower_name" placeholder="花束名称" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <div class="layui-input-inline" style="width: 200px;">
                                <input type="text" name="flower_sn" placeholder="花束编号" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <a class="layui-btn layui-btn-warm" id="screen"><i class="layui-icon">&#xe615;</i>查找</a>
                    </div>
                </form>
            </div>
            <table class="layui-hide" id="list" lay-filter="list" lay-data="{id: 'screen'}"></table>
        </div>
    </div>
    <script type="text/html" id="barDemo">
        <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
        <a class="layui-btn layui-btn-xs layui-btn-normal" lay-event="flower_photo">相册</a>
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
    </script>
    <script>
        layui.use('table', function(){
            var table = layui.table;
            var tableIns = table.render({
                elem: '#list'
                ,url: '/flower'
                ,height: 'full-270'
                ,page: {
                    layout: ['prev', 'page', 'next', 'count', 'skip']
                    ,groups: 5
                }
                ,limit: 20
                ,cols: [[
                    {field: 'id', title: 'ID', width:80, sort: true}
                    ,{field: 'flower_sn', title: '花束编号', width: 200}
                    ,{field: 'flower_name', title: '花束名称', width: 200}
                    ,{field: 'flower_brief', title: '花束简介', width: 200}
                    ,{field: 'price', title: '花束价格', width: 90}
                    ,{field: 'flower_number', title: '库存', width: 90}
                    ,{field: 'sales', title: '销量', width: 90}
                    ,{field: 'integral', title: '积分使用限额', width: 120}
                    ,{field: 'sales', title: '销量', width: 90}
                    ,{field: 'sales', title: '销量', width: 90}
                    ,{field: 'sort', title: '排序', width: 90, sort: true}
                    ,{field: 'status', title: '上架', width: 90, event: 'status', style:'cursor: pointer;', templet: function(d){if(d.status === 1){return '✔';}else{return '✘';}}}
                    ,{fixed: 'right', title:'操作', toolbar: '#barDemo'}
                ]]
            });
            $("#screen").click(function(){
                tableIns.reload({
                    where: {
                        flower_name: $("input[name='flower_name']").val(),
                        flower_sn: $("input[name='flower_sn']").val()
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
                            url: '/flower/'+data.id,
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
                }else if(layEvent === 'edit'){
                    window.location.href = "/flower/"+data.id+'/edit';
                }else if(layEvent === 'flower_photo'){
                    window.location.href = "/flower_photo/"+data.id;
                }else if(layEvent === 'status'){
                    layer.confirm('确定要修改销售状态吗?', function(index){
                        $.ajax({
                            type: 'post',
                            url: '/flower_status/'+data.id,
                            data: {'_token': '{{csrf_token()}}'},
                            dataType: 'json',
                            success: function(data){
                                if(data.code === 200){
                                    if(data.code === 200){
                                        if(data.data === 1){
                                            obj.update({status: '✔'});
                                        }else{
                                            obj.update({status: '✘'});
                                        }
                                        layer.msg(data.msg, {icon: 1});
                                    }else{
                                        layer.msg(data.msg, {icon: 5, anim: 6});
                                    }
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
                }
            });
        });
    </script>
@endsection