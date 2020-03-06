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
                <a href="/gift_card">卡片列表</a>
                <a><cite>实体卡列表</cite></a>
            </span>
        </div>
        <div class="admin-table">
            <div class="admin-search">
                {{--/entity_gift_card/create--}}
                <a href="javascript:add_gift_card();" class="layui-btn add">
                    <i class="layui-icon">&#xe608;</i> 生成实体卡
                </a>
                <form class="layui-form" action="">
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <div class="layui-input-inline" style="width: 200px;">
                                <input type="text" name="user_gift_card_sn" placeholder="礼品卡编号" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <a class="layui-btn layui-btn-warm" id="screen"><i class="layui-icon">&#xe615;</i>查找</a>
                    </div>
                </form>
            </div>
            <table id="list" lay-filter="list" lay-data="{id: 'screen'}"></table>
        </div>
    </div>
    <input type="hidden" id="gift_card_id" value="{{$gift_card_id}}">
    {{--<script type="text/html" id="barDemo">--}}
        {{--<a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>--}}
        {{--<a class="layui-btn layui-btn-xs layui-btn-normal" lay-event="entity">查看实体卡</a>--}}
        {{--<a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>--}}
    {{--</script>--}}
    <script>
        layui.use('table', function(){
            var table = layui.table;
            var tableIns = table.render({
                elem: '#list'
                ,url: '/entity_gift_card?gift_card_id='+$("#gift_card_id").val()
                ,height: 'full-270'
                ,page: {
                    layout: ['prev', 'page', 'next', 'count', 'skip']
                    ,groups: 5
                }
                ,limit: 20
                ,cols: [[
                    {field: 'id', title: 'ID', width:80, sort: true}
                    ,{field: 'user_gift_card_sn', title: '礼品卡编号'}
                    ,{field: 'password', title: '密码'}
                    ,{field: 'status', title: '状态', templet: function(d){if(d.status === 1){return '未绑定';}else{return '已绑定';}}}
//                    ,{fixed: 'right', title:'操作', toolbar: '#barDemo'}
                ]]
            });
            $("#screen").click(function(){
                tableIns.reload({
                    where: {
                        user_gift_card_sn: $("input[name='user_gift_card_sn']").val()
                    }
                    ,page: {
                        curr: 1
                    }
                });
            });
        });
        function add_gift_card(){
            layer.prompt({title: '输入生成卡片数量', formType: 0}, function(number){
                layer.close(index);
                var index = layer.load(2, {shade: [0.1, '#000000']});
                $.ajax({
                    type: 'post',
                    url: '/entity_gift_card',
                    data: {'_token': '{{csrf_token()}}', 'gift_card_id': $("#gift_card_id").val(), 'number': number},
                    dataType: 'json',
                    success: function(data){
                        layer.close(index);
                        if(data.code === 200){
                            layer.msg(data.msg, {icon: 1});
                            window.location.reload();
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
    </script>
@endsection