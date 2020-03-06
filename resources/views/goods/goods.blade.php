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
                <a><cite>商品列表</cite></a>
            </span>
        </div>
        <div class="admin-table">
            <div class="admin-search">
                <a href="/goods/create" class="layui-btn add">
                    <i class="layui-icon">&#xe608;</i> 添加商品
                </a>
                <form class="layui-form" action="">
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <div class="layui-input-inline" style="width: 200px;">
                                <input type="text" name="goods_name" placeholder="商品名称" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <div class="layui-input-inline" style="width: 200px;">
                                <input type="text" name="goods_sn" placeholder="商品编号" autocomplete="off" class="layui-input">
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
        <a class="layui-btn layui-btn-xs" lay-event="goods_info">详情编辑</a>
        <a class="layui-btn layui-btn-xs layui-btn-normal" lay-event="goods_attribute">入库</a>
        <a class="layui-btn layui-btn-xs layui-btn-normal" lay-event="goods_photo">商品相册</a>
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
    </script>
    <script>
        layui.use('table', function(){
            var table = layui.table;
            var tableIns = table.render({
                elem: '#list'
                ,url: '/goods'
                ,height: 'full-270'
                ,page: {
                    layout: ['prev', 'page', 'next', 'count', 'skip']
                    ,groups: 5
                }
                ,limit: 20
                ,cols: [[
                    {field: 'id', title: 'ID', width:80, sort: true}
                    ,{field: 'goods_sn', title: '商品编号', width: 190}
                    ,{field: 'cat_id', title: '商品分类', width: 90, templet: function(d){return d.cat_id.name}}
                    ,{field: 'goods_name', title: '商品名称', width: 150}
                    ,{field: 'market_price', title: '市场价', width: 80}
                    ,{field: 'shop_price', title: '本店价', width: 80}
                    ,{field: 'vip_price', title: '会员价', width: 80}
                    ,{field: 'promote_price', title: '优惠价', width: 80}
                    ,{field: 'goods_number', title: '库存', width: 80}
                    ,{field: 'is_on_sale', title: '上架', width: 60, event: 'is_on_sale', style:'cursor: pointer;', templet: function(d){if(d.is_on_sale === 1){return '✔';}else{return '✘';}}}
                    ,{field: 'is_alone_sale', title: '赠品', width: 60, event: 'is_alone_sale', style:'cursor: pointer;', templet: function(d){if(d.is_alone_sale === 1){return '✘';}else{return '✔';}}}
                    ,{field: 'is_best', title: '精品', width: 60, event: 'is_best', style:'cursor: pointer;', templet: function(d){if(d.is_best === 1){return '✔';}else{return '✘';}}}
                    ,{field: 'is_new', title: '新品', width: 60, event: 'is_new', style:'cursor: pointer;', templet: function(d){if(d.is_new === 1){return '✔';}else{return '✘';}}}
                    ,{field: 'is_hot', title: '热销', width: 60, event: 'is_hot', style:'cursor: pointer;', templet: function(d){if(d.is_hot === 1){return '✔';}else{return '✘';}}}
                    ,{field: 'is_promote', title: '促销', width: 60, event: 'is_promote', style:'cursor: pointer;', templet: function(d){if(d.is_promote === 1){return '✔';}else{return '✘';}}}
                    ,{fixed: 'right', title:'操作', toolbar: '#barDemo'}
                ]]
            });
            $("#screen").click(function(){
                tableIns.reload({
                    where: {
                        goods_name: $("input[name='goods_name']").val(),
                        goods_sn: $("input[name='goods_sn']").val()
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
                            url: '/goods/'+data.id,
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
                    window.location.href = "/goods/"+data.id+'/edit';
                }else if(layEvent === 'goods_info'){
                    window.location.href = "/goods_info?goods_id="+data.id;
                }else if(layEvent === 'goods_attribute'){
                    window.location.href = "/goods_attribute?goods_id="+data.id;
                }else if(layEvent === 'goods_photo'){
                    window.location.href = "/goods_photo/"+data.id;
                }else if(layEvent === 'is_on_sale'){
                    layer.confirm('确定要修改销售状态吗?', function(index){
                        $.ajax({
                            type: 'post',
                            url: '/goods_is_on_sale/'+data.id,
                            data: {'_token': '{{csrf_token()}}'},
                            dataType: 'json',
                            success: function(data){
                                if(data.code === 200){
                                    if(data.code === 200){
                                        if(data.data === 1){
                                            obj.update({is_on_sale: '✔'});
                                        }else{
                                            obj.update({is_on_sale: '✘'});
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
                }else if(layEvent === 'is_alone_sale'){
                    layer.confirm('确定要修改赠品状态吗?', function(index){
                        $.ajax({
                            type: 'post',
                            url: '/goods_is_alone_sale/'+data.id,
                            data: {'_token': '{{csrf_token()}}'},
                            dataType: 'json',
                            success: function(data){
                                if(data.code === 200){
                                    if(data.code === 200){
                                        if(data.data === 1){
                                            obj.update({is_alone_sale: '✘'});
                                        }else{
                                            obj.update({is_alone_sale: '✔'});
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
                }else if(layEvent === 'is_best'){
                    layer.confirm('确定要修改精品状态吗?', function(index){
                        $.ajax({
                            type: 'post',
                            url: '/goods_is_best/'+data.id,
                            data: {'_token': '{{csrf_token()}}'},
                            dataType: 'json',
                            success: function(data){
                                if(data.code === 200){
                                    if(data.code === 200){
                                        if(data.data === 1){
                                            obj.update({is_best: '✔'});
                                        }else{
                                            obj.update({is_best: '✘'});
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
                }else if(layEvent === 'is_new'){
                    layer.confirm('确定要修改新品状态吗?', function(index){
                        $.ajax({
                            type: 'post',
                            url: '/goods_is_new/'+data.id,
                            data: {'_token': '{{csrf_token()}}'},
                            dataType: 'json',
                            success: function(data){
                                if(data.code === 200){
                                    if(data.code === 200){
                                        if(data.data === 1){
                                            obj.update({is_new: '✔'});
                                        }else{
                                            obj.update({is_new: '✘'});
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
                }else if(layEvent === 'is_hot'){
                    layer.confirm('确定要修改热销状态吗?', function(index){
                        $.ajax({
                            type: 'post',
                            url: '/goods_is_hot/'+data.id,
                            data: {'_token': '{{csrf_token()}}'},
                            dataType: 'json',
                            success: function(data){
                                if(data.code === 200){
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
                            url: '/goods_is_promote/'+data.id,
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