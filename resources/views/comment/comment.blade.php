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
                <a><cite>晒一晒列表</cite></a>
            </span>
        </div>
        <div class="admin-table">
            <div class="admin-search">
                <a href="/comment/create" class="layui-btn add">
                    <i class="layui-icon">&#xe608;</i> 发布晒一晒
                </a>
                <form class="layui-form" action="">
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <div class="layui-input-inline" style="width: 200px;">
                                <input type="text" name="comment_sn" placeholder="晒一晒名称" autocomplete="off" class="layui-input">
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
        <a class="layui-btn layui-btn-xs" lay-event="edit">回复</a>
        <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit">查看回复</a>
        {{--<a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>--}}
    </script>
    <script>
        layui.use('table', function(){
            var table = layui.table;
            var tableIns = table.render({
                elem: '#list'
                ,url: '/comment'
                ,height: 'full-270'
                ,page: {
                    layout: ['prev', 'page', 'next', 'count', 'skip']
                    ,groups: 5
                }
                ,limit: 20
                ,cols: [[
                    {field: 'id', title: 'ID', width:80, sort: true}
                    ,{field: 'user_name', title: '用户名', width:150}
                    ,{field: 'shop_name', title: '门店', width:150}
                    ,{field: 'comment_content', title: '内容', width: 300}
                    ,{field: 'is_img', title: '图片', width: 80, templet: function(d){if(d.is_img === 1){return '没有';}else{return '有图';}}}
                    ,{field: 'is_hot', title: '热门', width: 80, event: 'hot', style:'cursor: pointer;', templet: function(d){if(d.is_hot === 1){return '✘';}else{return '✔';}}}
                    ,{field: 'is_top', title: '置顶', width: 80, event: 'top', style:'cursor: pointer;', templet: function(d){if(d.is_top === 1){return '✘';}else{return '✔';}}}
                    ,{field: 'is_recommend', title: '推荐', width: 80, event: 'recommend', style:'cursor: pointer;', templet: function(d){if(d.is_recommend === 1){return '✘';}else{return '✔';}}}
                    ,{field: 'status', title: '状态', width: 80, event: 'status', style:'cursor: pointer;', templet: function(d){if(d.status === 1){return '显示';}else{return '隐藏';}}}
                    ,{field: 'created_at', title: '发布时间', width: 170, sort: true}
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
                        comment_sn: $("input[name='comment_sn']").val()
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
                }else if(layEvent === 'hot'){
                    layer.confirm('确定要修改热门状态吗?', function(index){
                        $.ajax({
                            type: 'post',
                            url: '/comment_hot/'+data.id,
                            data: {'_token': '{{csrf_token()}}'},
                            dataType: 'json',
                            success: function(data){
                                if(data.code === 200){
                                    if(data.code === 200){
                                        if(data.data === 1){
                                            obj.update({is_hot: '✘'});
                                        }else{
                                            obj.update({is_hot: '✔'});
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
                }else if(layEvent === 'top'){
                    layer.confirm('确定要修改置顶状态吗?', function(index){
                        $.ajax({
                            type: 'post',
                            url: '/comment_top/'+data.id,
                            data: {'_token': '{{csrf_token()}}'},
                            dataType: 'json',
                            success: function(data){
                                if(data.code === 200){
                                    if(data.code === 200){
                                        if(data.data === 1){
                                            obj.update({is_top: '✘'});
                                        }else{
                                            obj.update({is_top: '✔'});
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
                }else if(layEvent === 'recommend'){
                    layer.confirm('确定要修改置顶状态吗?', function(index){
                        $.ajax({
                            type: 'post',
                            url: '/comment_recommend/'+data.id,
                            data: {'_token': '{{csrf_token()}}'},
                            dataType: 'json',
                            success: function(data){
                                if(data.code === 200){
                                    if(data.code === 200){
                                        if(data.data === 1){
                                            obj.update({is_recommend: '✘'});
                                        }else{
                                            obj.update({is_recommend: '✔'});
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
                } else if(layEvent === 'status'){
                    layer.confirm('确定要修改此晒一晒状态吗?', function(index){
                        $.ajax({
                            type: 'post',
                            url: '/comment_status/'+data.id,
                            data: {'_token': '{{csrf_token()}}'},
                            dataType: 'json',
                            success: function(data){
                                if(data.code === 200){
                                    obj.update({
                                        status: data.msg
                                    });
                                    layer.msg('已'+data.msg, {icon: 1});
                                }else{
                                    layer.msg('已'+data.msg, {icon: 5, anim: 6});
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