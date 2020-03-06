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
    <input type="hidden" name="shop_id" value="{{$shop_id}}">
    <div class="admin-overall">
        <div class="admin-nav">
            <span class="layui-breadcrumb">
                <a href="/">主页</a>
                <a href="/shop">门店列表</a>
                <a><cite>服务项目</cite></a>
            </span>
        </div>
        <div class="admin-table">
            <div class="admin-search">
                <a href="/shop_serve/create?id={{$shop_id}}" class="layui-btn add">
                    <i class="layui-icon">&#xe608;</i> 添加项目
                </a>
                <form class="layui-form" action="">
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <div class="layui-input-inline" style="width: 200px;">
                                <input type="text" name="serve_name" placeholder="项目名称" autocomplete="off" class="layui-input">
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
        <a class="layui-btn layui-btn-xs layui-btn-normal" lay-event="shop_serve_photo">相册</a>
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
    </script>
    <script>
        layui.use('table', function(){
            var table = layui.table;
            var tableIns = table.render({
                elem: '#list'
                ,url: '/shop_serve/'+$("input[name='shop_id']").val()
                ,height: 'full-270'
                ,page: {
                    layout: ['prev', 'page', 'next', 'count', 'skip']
                    ,groups: 5
                }
                ,limit: 20
                ,cols: [[
                    {field: 'id', title: 'ID', width:80, sort: true}
                    ,{field: 'serve_name', title: '服务名称', width: 200}
                    ,{field: 'market_price', title: '市场价', width: 90}
                    ,{field: 'shop_price', title: '本店价格', width: 90}
                    ,{field: 'give_integral', title: '送消费积分', width: 100}
                    ,{field: 'rank_integral', title: '送等级积分', width: 100}
                    ,{field: 'sales', title: '销量', width: 90}
                    ,{field: 'is_on_sale', title: '销售状态', width: 90, event: 'is_on_sale', style:'cursor: pointer;', templet: function(d){if(d.is_on_sale === 1){return '上架';}else{return '下架';}}}
                    ,{field: 'is_hot', title: '是否热销', width: 90, event: 'is_hot', style:'cursor: pointer;', templet: function(d){if(d.is_hot === 1){return '✔';}else{return '✘';}}}
                    ,{field: 'is_promote', title: '是否促销', width: 90, event: 'is_promote', style:'cursor: pointer;', templet: function(d){if(d.is_promote === 1){return '✔';}else{return '✘';}}}
                    ,{fixed: 'right', title:'操作', toolbar: '#barDemo'}
                ]]
            });
            $("#screen").click(function(){
                tableIns.reload({
                    where: {
                        serve_name: $("input[name='serve_name']").val()
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
                            url: '/shop_serve/'+data.id,
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
                    window.location.href = "/shop_serve/"+data.id+'/edit';
                }else if(layEvent === 'shop_serve_photo'){
                    window.location.href = "/shop_serve_photo/"+data.id;
                }else if(layEvent === 'is_on_sale'){
                    layer.confirm('确定要修改销售状态吗?', function(index){
                        $.ajax({
                            type: 'post',
                            url: '/shop_serve_sale/'+data.id,
                            data: {'_token': '{{csrf_token()}}'},
                            dataType: 'json',
                            success: function(data){
                                if(data.code === 200){
                                    obj.update({
                                        is_on_sale: data.msg
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
                }else if(layEvent === 'is_hot'){
                    layer.confirm('确定要修改热销状态吗?', function(index){
                        $.ajax({
                            type: 'post',
                            url: '/shop_serve_hot/'+data.id,
                            data: {'_token': '{{csrf_token()}}'},
                            dataType: 'json',
                            success: function(data){
                                if(data.code === 200){
                                    if(data.data === 1){
                                        obj.update({is_hot: '✔'});
                                    }else{
                                        obj.update({is_hot: '✘'});
                                    }
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
                }else if(layEvent === 'is_promote'){
                    layer.confirm('确定要修改促销状态吗?', function(index){
                        $.ajax({
                            type: 'post',
                            url: '/shop_serve_promote/'+data.id,
                            data: {'_token': '{{csrf_token()}}'},
                            dataType: 'json',
                            success: function(data){
                                if(data.code === 200){
                                    if(data.data === 1){
                                        obj.update({is_promote: '✔'});
                                    }else{
                                        obj.update({is_promote: '✘'});
                                    }
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