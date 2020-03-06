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
                <a><cite>轮播图列表</cite></a>
            </span>
        </div>
        <div class="admin-table">
            <div class="admin-search">
                <a href="/banner/create" class="layui-btn add">
                    <i class="layui-icon">&#xe608;</i> 添加轮播图
                </a>
                <form class="layui-form" action="">
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <div class="layui-input-inline" style="width: 200px;">
                                <input type="text" name="name" placeholder="轮播名称" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <div class="layui-input-inline" style="width: 200px;">
                                <select id="type_id" name="type_id" lay-verify="required">
                                    <option value="">轮播图类别</option>
                                    @foreach($banner_type as $k => $v)
                                        <option value="{{$v->id}}">{{$v->name}}</option>
                                    @endforeach
                                </select>
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
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
    </script>
    <script>
        layui.use('table', function(){
            var table = layui.table;
            var tableIns = table.render({
                elem: '#list'
                ,url: '/banner'
                ,height: 'full-270'
                ,page: {
                    layout: ['prev', 'page', 'next', 'count', 'skip']
                    ,groups: 5
                }
                ,limit: 20
                ,cols: [[
                    {field: 'id', title: 'ID', width:80, sort: true}
                    ,{field: 'img_url', title: '轮播图片', width:250, style:'cursor: pointer;', templet: function(d){return '<div class="layer-photos-demo"><img layer-pid="'+d.id+'" layer-src="'+d.img_url+'" src="'+d.img_url+'" alt="'+d.name+'"></div>';}}
                    ,{field: 'type_name', title: '类别名称', width:150}
                    ,{field: 'name', title: '名称', width:150}
                    ,{field: 'link', title: '链接', width: 300}
                    ,{field: 'start_time', title: '开始时间', width: 170, sort: true}
                    ,{field: 'end_time', title: '结束时间', width: 170, sort: true}
                    ,{field: 'status', title: '状态', width: 80, event: 'setSign', style:'cursor: pointer;', templet: function(d){if(d.status === 1){return '启用';}else{return '禁用';}}}
                    ,{fixed: 'right', title:'操作', toolbar: '#barDemo'}
                ]]
                ,done: function(res, curr, count){
                    var rand = parseInt(Math.random() * 7);
                    layer.photos({
                        photos: '.layer-photos-demo'
                        ,anim: rand
                    });
                }
            });
            $("#screen").click(function(){
                tableIns.reload({
                    where: {
                        name: $("input[name='name']").val()
                        ,type_id: $("#type_id").val()
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
                            url: '/banner/'+data.id,
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
                    window.location.href = "/banner/"+data.id+'/edit';
                } else if(layEvent === 'setSign'){
                    layer.confirm('确定要修改此轮播图状态吗?', function(index){
                        $.ajax({
                            type: 'post',
                            url: '/banner_status/'+data.id,
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
                }
            });
        });
    </script>
@endsection