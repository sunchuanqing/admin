@extends('index')
@section('content')
    <style>
        .admin-overall{width: 100%; height: 100%;}
        .admin-nav{height: 50px; background-color: #ffffff; line-height: 50px; font-size: 14px; padding-left: 15px;}
        .admin-table{margin: 15px; padding: 0 15px 15px 15px; background-color: #ffffff; border-radius: 5px; height: calc(100% - 95px);}
        .admin-search{width: 100%; height: 60px; border-bottom: 1px solid #f2f2f2 !important;}
        .add{float: right; margin-top: 11px;}
    </style>
    <div class="admin-overall">
        <div class="admin-nav">
            <span class="layui-breadcrumb">
                <a href="/">主页</a>
                <a href="/user">会员列表</a>
                <a><cite>会员账户</cite></a>
            </span>
        </div>
        <input type="hidden" name="user_id" value="{{$user['id']}}">
        <div class="admin-table">
            <div class="admin-search">
                <a href="javascript:void(0);" id="account_add" class="layui-btn add">
                    <i class="layui-icon">&#xe608;</i> 账户充值
                </a>
                <div style="height: 40px; background: #f2f2f2; float: left; line-height: 40px; margin-top: 10px; padding-left: 10px; padding-right: 10px; font-size: 14px; color: #666;">
                    当前会员：{{$user['user_name']}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    可用资金：<span id="user_money">{{$user['user_money']}}</span>
                </div>
            </div>
            <table id="list" lay-filter="list" lay-data="{id: 'screen'}"></table>
        </div>
    </div>
    <script>
        layui.use(['table', 'layer'], function(){
            var table = layui.table;
            var tableIns = table.render({
                elem: '#list'
                ,url: '/user_account/'+$("input[name='user_id']").val()
                ,height: 'full-270'
                ,page: {
                    layout: ['prev', 'page', 'next', 'count', 'skip']
                    ,groups: 5
                }
                ,limit: 20
                ,cols: [[
                    {field: 'account_sn', title: '流水编号', width:200}
                    ,{field: 'change_name', title: '变动名称', width:150}
                    ,{field: 'change_type', title: '类别', width:100, templet: function(d){if(d.change_type === 1){return '储值金';}else if(d.change_type === 2){return '微信';}else if(d.change_type === 3){return '支付宝';}else if(d.change_type === 4){return '银行卡';}else if(d.change_type === 5){return '现金';}else if(d.change_type === 6){return '免单';}else if(d.change_type === 7){return '赠送金';}else if(d.change_type === 0){return '后台充值';}}}
                    ,{field: 'money_change', title: '变动金额', width:200}
                    ,{field: 'money', title: '账户余额', width:200}
                    ,{field: 'created_at', title: '变动日期', width: 300, sort: true}
                    ,{field: 'change_desc', title: '记录'}
                ]]
            });

            // 会员账户充值
            $('#account_add').click(function(){
                layer.prompt({title: '充值金额', formType: 0}, function(money_change, index){
                    layer.close(index);
                    layer.prompt({title: '充值记录', formType: 2}, function(change_desc, index){
                        layer.close(index);
                        index = layer.load(2, {shade: [0.1, '#000000']});
                        $.ajax({
                            type: 'post',
                            url: '/user_account',
                            data: {'_token': '{{csrf_token()}}', 'money_change': money_change, 'change_desc': change_desc, 'user_id': $("input[name='user_id']").val()},
                            dataType: 'json',
                            success: function(data){
                                layer.close(index);
                                if(data.code === 200){
                                    $('#user_money').html(data.data.money);
                                    tableIns.reload(screen);
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
                });
            });
        });
    </script>
@endsection