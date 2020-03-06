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
                <a><cite>优惠券列表</cite></a>
            </span>
        </div>
        <div class="admin-table">
            <div class="admin-search">
                <a href="/coupon/create" class="layui-btn add">
                    <i class="layui-icon">&#xe608;</i> 添加优惠券
                </a>
                <form class="layui-form" action="">
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <div class="layui-input-inline" style="width: 200px;">
                                <input type="text" name="name" placeholder="优惠券名称" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <a class="layui-btn layui-btn-warm" id="screen"><i class="layui-icon">&#xe615;</i>查找</a>
                    </div>
                </form>
            </div>
            <table id="list" lay-filter="list" lay-data="{id: 'screen'}"></table>
        </div>
    </div>
    <script>
        layui.use('table', function(){
            var table = layui.table;
            var tableIns = table.render({
                elem: '#list'
                ,url: '/coupon'
                ,height: 'full-270'
                ,page: {
                    layout: ['prev', 'page', 'next', 'count', 'skip']
                    ,groups: 5
                }
                ,limit: 20
                ,cols: [[
                    {field: 'id', title: 'ID', width:80, sort: true}
                    ,{field: 'name', title: '优惠券名称', width:200}
                    ,{field: 'coupon_type', title: '优惠券类别', width:100, templet: function(d){return d.coupon_types.coupon_type_name}}
                    ,{field: 'subject_type', title: '使用主体', width:120, event: 'setSign', style:'cursor: pointer;', templet: function(d){if(d.subject_type === 1){return '通用';}else if(d.subject_type === 2){if(d.shop_id === 0){return '门店(未绑定)';}else{return '门店(ID：'+d.shop_id+')'}}else if(d.subject_type === 3){return '好货';}else if(d.subject_type === 4){return '指定商品';}}}
                    ,{field: 'money', title: '优惠券金额', width:100, templet: function(d){return d.coupon_types.money}}
                    ,{field: 'full_money', title: '使用下线', width:100, templet: function(d){return d.coupon_types.full_money}}
                    ,{field: 'pay_type', title: '费用', width:80, templet: function(d){if(d.pay_type === 1){return '免费';}else{return d.pay_money;}}}
                    ,{field: 'number', title: '剩余总量', width:90}
                    ,{field: 'user_number', title: '领取限额', width: 90}
                    ,{field: 'valid_type', title: '时效', width:180, templet: function(d){if(d.valid_type === 1){return '绝对时效：'+d.valid_start_time+' - '+d.valid_end_time;}else{return '相对时效：'+d.valid_day+' 天有效期';}}}
                    ,{field: 'grant_type', title: '发放类型', width:90, templet: function(d){if(d.grant_type === 1){return '后台发放';}else if(d.grant_type === 2){return '用户领取';}else if(d.grant_type === 3){return '系统发放';}}}
                    ,{field: 'status', title: '状态', width: 80, templet: function(d){if(d.status === 1){return '未检测';}else if(d.status === 2){return '正常';}else{return '禁用'}}}
                    ,{fixed: 'right', title:'操作', templet: function(d){if(d.grant_type === 1){return '<a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a><a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="coupon_examine">检测状态</a><a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="shop_user">发放</a><a class="layui-btn layui-btn-warm layui-btn-xs" lay-event="shop_serve">报表</a><a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>';}else{return '<a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a><a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="coupon_examine">检测状态</a><a class="layui-btn layui-btn-disabled layui-btn-xs">发放</a><a class="layui-btn layui-btn-warm layui-btn-xs" lay-event="shop_serve">报表</a><a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>';}}}
                ]]
            });
            $("#screen").click(function(){
                tableIns.reload({
                    where: {
                        name: $("input[name='name']").val()
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
                            url: '/coupon/'+data.id,
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
                    window.location.href = "/coupon/"+data.id+'/edit';
                } else if (layEvent === 'coupon_examine'){
                    $.ajax({
                        type: 'post',
                        url: '/coupon_examine',
                        data: {'_token': '{{csrf_token()}}', 'id': data.id, 'subject_type': data.subject_type},
                        dataType: 'json',
                        success: function(data){
                            if(data.code === 200){
                                obj.update({
                                    status: '正常'
                                });
                                layer.msg(data.msg, {icon: 1});
                            }else{
                                layer.confirm(data.msg, {
                                    btn: ['绑定','取消'] //按钮
                                }, function(){
                                    window.location.href = "/coupon_subject/"+data.data.id;
                                });
                            }
                        },
                        error: function(){
                            layer.close(index);
                            layer.msg('网络异常请重试', {icon: 5, anim: 6});
                        }
                    });
                } else if (layEvent === 'shop_user'){
                    layer.confirm('您确定要发放吗?', function(index){
                        layer.close(index);
                        layer.msg('正在发放中', {icon: 16, shade: [0.1, '#000000'], time: 0});
                        $.ajax({
                            type: 'post',
                            url: '/coupon_user',
                            data: {'_token': '{{csrf_token()}}', 'id': data.id},
                            dataType: 'json',
                            success: function(data){
                                layer.closeAll();
                                if(data.code === 200){
                                    obj.update({
                                        number: data.data
                                    });
                                    layer.msg(data.msg, {icon: 1});
                                }else{
                                    layer.msg(data.msg, {icon: 5, anim: 6});
                                }
                            },
                            error: function(){
                                layer.closeAll();
                                layer.msg('网络异常请重试', {icon: 5, anim: 6});
                            }
                        });
                    });
                } else if (layEvent === 'setSign'){
                    window.location.href = "/coupon_subject/"+data.id;
                }
            });
        });
    </script>
@endsection