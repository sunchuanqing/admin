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
                <a><cite>门店列表</cite></a>
            </span>
        </div>
        <div class="admin-table">
            <div class="admin-search">
                <a href="/shop/create" class="layui-btn add">
                    <i class="layui-icon">&#xe608;</i> 添加门店
                </a>
                <form class="layui-form" action="">
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <div class="layui-input-inline" style="width: 200px;">
                                <input type="text" name="shop_name" placeholder="门店名称" autocomplete="off" class="layui-input">
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
        <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="shop_photo">门店相册</a>
        <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="shop_serve">优惠服务</a>
        <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="shop_price_list">价目表</a>
        <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="shop_price">价目表分类</a>
        <a class="layui-btn layui-btn-warm layui-btn-xs" lay-event="edit">优惠券</a>
        <a class="layui-btn layui-btn-warm layui-btn-xs" lay-event="edit">晒一晒</a>
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
    </script>
    <script>
        layui.use('table', function(){
            var table = layui.table;
            var tableIns = table.render({
                elem: '#list'
                ,url: '/shop'
                ,height: 'full-270'
                ,page: {
                    layout: ['prev', 'page', 'next', 'count', 'skip']
                    ,groups: 5
                }
                ,limit: 20
                ,cols: [[
                    {field: 'id', title: 'ID', width:80, sort: true}
                    ,{field: 'shop_type_id', title: '门店类型', width:150, templet: function(d){return d.shop_type.type_name}}
                    ,{field: 'shop_name', title: '门店名称', width:200}
                    ,{field: 'shop_admin', title: '门店负责人', width:150}
                    ,{field: 'shop_phone', title: '联系电话', width: 150}
                    ,{field: 'address', title: '门店地址', width: 200}
                    ,{fixed: 'right', title:'操作', toolbar: '#barDemo'}
                ]]
            });
            $("#screen").click(function(){
                tableIns.reload({
                    where: {
                        shop_name: $("input[name='shop_name']").val()
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
                            url: '/shop/'+data.id,
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
                    window.location.href = "/shop/"+data.id+'/edit';
                } else if (layEvent === 'shop_photo'){
                    window.location.href = "/shop_photo/"+data.id;
                } else if (layEvent === 'shop_serve'){
                    window.location.href = "/shop_serve/"+data.id;
                } else if (layEvent === 'shop_price'){
                    window.location.href = "/shop_price/"+data.id;
                } else if (layEvent === 'shop_price_list'){
                    window.location.href = "/shop_price_list/"+data.id;
                } else if (layEvent === 'setSign'){
                    layer.confirm('确定要修改营业状态吗?', function(index){
                        $.ajax({
                            type: 'post',
                            url: '/shop_status/'+data.id,
                            data: {'_token': '{{csrf_token()}}'},
                            dataType: 'json',
                            success: function(data){
                                if(data.code === 200){
                                    obj.update({
                                        shop_status: data.msg
                                    });
                                    layer.msg('改为'+data.msg, {icon: 1});
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