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
                <a><cite>会员列表</cite></a>
            </span>
        </div>
        <div class="admin-table">
            <div class="admin-search">
                <a href="/user/create" class="layui-btn add">
                    <i class="layui-icon">&#xe608;</i> 添加会员
                </a>
                <form class="layui-form" action="">
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <div class="layui-input-inline" style="width: 200px;">
                                <input type="text" name="user_name" placeholder="用户名" autocomplete="off" class="layui-input">
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
        {{--<a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="password">修改密码</a>--}}
        {{--<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="address">收货地址</a>--}}
        <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="order">订单</a>
        <a class="layui-btn layui-btn-warm layui-btn-xs" lay-event="account">账户</a>
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
    </script>
    <script>
        layui.use('table', function(){
            var table = layui.table;
            var tableIns = table.render({
                elem: '#list'
                ,url: '/user'
                ,height: 'full-270'
                ,page: {
                    layout: ['prev', 'page', 'next', 'count', 'skip']
                    ,groups: 5
                }
                ,limit: 20
                ,cols: [[
                    {field: 'id', title: 'ID', width:80, sort: true}
                    ,{field: 'user_name', title: '用户名', width:150}
                    ,{field: 'user_sn', title: '会员卡号', width:150}
                    ,{field: 'phone', title: '手机号', width:150}
                    ,{field: 'source_msg', title: '来源', width:100}
                    ,{field: 'user_money', title: '储值金', width: 100}
                    ,{field: 'give_money', title: '赠送金', width: 100}
                    ,{field: 'gift_card_money', title: '礼品卡', width: 100}
                    ,{field: 'pay_points', title: '积分', width: 100}
                    ,{field: 'flag', title: '状态', width: 100, event: 'setSign', style:'cursor: pointer;', templet: function(d){if(d.flag === 1){return '启用';}else{return '禁用';}}}
                    ,{fixed: 'right', title:'操作', toolbar: '#barDemo'}
                ]]
            });
            $("#screen").click(function(){
                tableIns.reload({
                    where: {
                        user_name: $("input[name='user_name']").val()
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
                            url: '/user/'+data.id,
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
                    window.location.href = "/user/"+data.id+'/edit';
                } else if(layEvent === 'password'){
                    window.location.href = "/user_pass/"+data.id;
                } else if(layEvent === 'address'){
                    window.location.href = "/user_address_only/"+data.id;
                } else if(layEvent === 'account'){
                    window.location.href = "/user_account/"+data.id;
                } else if(layEvent === 'setSign'){
                    layer.confirm('确定要修改此会员状态吗?', function(index){
                        $.ajax({
                            type: 'post',
                            url: '/flag/'+data.id,
                            data: {'_token': '{{csrf_token()}}'},
                            dataType: 'json',
                            success: function(data){
                                if(data.code === 200){
                                    obj.update({
                                        flag: data.msg
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