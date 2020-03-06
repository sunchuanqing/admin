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
        <input type="hidden" id="goods_id" value="{{$goods->id}}">
        <input type="hidden" id="goods_attr" value="{{$goods->goods_attr}}">
        <div class="admin-nav">
            <span class="layui-breadcrumb">
                <a href="/">主页</a>
                <a href="/goods">商品列表</a>
                <a><cite>商品属性</cite></a>
            </span>
        </div>
        <div class="admin-table">
            <div class="admin-search">
                <a href="/goods_attribute/create?goods_id={{$goods['id']}}" class="layui-btn add">
                    <i class="layui-icon">&#xe608;</i> 添加属性
                </a>
                <form class="layui-form" action="">
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <div class="layui-input-inline" style="width: 200px;">
                                <input type="text" name="attr_name" placeholder="属性名称" autocomplete="off" class="layui-input">
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
        <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="goods_number">添加库存</a>
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
    </script>
    <script>
        layui.use('table', function(){
            var table = layui.table;
            var ss = $("#goods_attr").val();
            var tableIns = table.render({
                elem: '#list'
                ,url: '/goods_attribute?goods_id='+$("#goods_id").val()
                ,height: 'full-270'
                ,page: {
                    layout: ['prev', 'page', 'next', 'count', 'skip']
                    ,groups: 5
                }
                ,limit: 20
                ,cols: [[
                    {field: 'id', title: 'ID', width:80, sort: true}
                    ,{field: 'attr_thumb', title: '属性图片', width:200, style:'cursor: pointer;', templet: function(d){return '<div class="layer-photos-demo"><img layer-pid="'+d.id+'" layer-src="'+d.attr_original_img+'" src="'+d.attr_thumb+'" alt="'+d.attr_name+'"></div>';}}
                    ,{field: 'attr_name', title: '属性名称', width:400}
                    ,{field: 'attr_money', title: '属性加价', width:100}
                    ,{field: 'attr_number', title: '属性库存', width:100}
                    ,{field: 'attr_sort', title: '属性排序', width:100}
//                    ,{field: 'goods_attr', title: '是否默认', width:100, event: 'goods_attr', style:'cursor: pointer;', templet: function(d){if(d.id == ss){return '✔';}else{return '✘';}}}
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
                        attr_name: $("input[name='attr_name']").val()
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
                            url: '/goods_attribute/'+data.id,
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
                } else if (layEvent === 'edit'){
                    window.location.href = "/goods_attribute/"+data.id+'/edit';
                }else if (layEvent === 'goods_number'){
                    layer.prompt({
                        formType: 0,
                        value: '',
                        title: '入库数量'
                    }, function(value, index, elem){
                        $.ajax({
                            type: 'post',
                            url: '/goods_number/'+data.id,
                            data: {'_token': '{{csrf_token()}}', 'attr_number': value},
                            dataType: 'json',
                            success: function(data){
                                layer.close(index);
                                if(data.code === 200){
                                    obj.update({
                                        attr_number: data.data
                                    });
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
                }
            });
        });
    </script>
@endsection