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
        .print_table th {height: 30px; padding: 0 20px;}
    </style>
    <div class="admin-overall">
        <div class="admin-nav">
            <span class="layui-breadcrumb">
                <a href="/">主页</a>
                <a><cite>订单列表</cite></a>
            </span>
        </div>
        <div class="admin-table">
            <div class="admin-search">
                <form class="layui-form" action="">
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <div class="layui-input-inline" style="width: 200px;">
                                <input type="text" name="order_sn" placeholder="订单编号" autocomplete="off" class="layui-input">
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
        <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="show">详情</a>
    </script>
    <script>
        layui.use('table', function(){
            var table = layui.table;
            var tableIns = table.render({
                elem: '#list'
                ,url: '/giftcard_order'
                ,height: 'full-270'
                ,page: {
                    layout: ['prev', 'page', 'next', 'count', 'skip']
                    ,groups: 5
                }
                ,limit: 20
                ,cols: [[
                    {field: 'id', title: 'ID', width:80, sort: true}
                    ,{field: 'order_sn', title: '订单编号', width:240}
                    ,{field: 'order_type', title: '订单类别', width:100, templet: function(d){if(d.order_type === 1){return '奢饰品护理';}else if(d.order_type === 2){return '名车护理';}else if(d.order_type === 3){return '花艺';}else if(d.order_type === 4){return '优惠券';}else if(d.order_type === 5){return '优惠服务';}else if(d.order_type === 6){return '好货';}else if(d.order_type === 7){return '车护年卡';}else if(d.order_type === 8){return '礼品卡';}}}
                    ,{field: 'user_name', title: '下单账户', width:150, templet: function(d){return d.user_name+'（ID：'+d.user_id+'）';}}
                    ,{field: 'consignee', title: '联系人', width:100}
                    ,{field: 'phone', title: '收货人电话', width:120}
                    ,{field: 'order_amount', title: '实际付款', width:90}
                    ,{field: 'order_status', title: '订单状态', width: 90, templet: function(d){if(d.order_status === 1){return '已预约';}else if(d.order_status === 2){return '洗护中';}else if(d.order_status === 3){return '洗护完工';}else if(d.order_status === 4){return '已完成';}else if(d.order_status === 5){return '已取消';}else if(d.order_status === 6){return '已下单';}else if(d.order_status === 7){return '制作中';}else if(d.order_status === 8){return '制作完成';}}}
                    ,{field: 'shipping_status', title: '物流状态', width: 90, templet: function(d){if(d.shipping_status === 1){return '未揽件';}else if(d.shipping_status === 2){return '已揽件';}else if(d.shipping_status === 3){return '已接收';}else if(d.shipping_status === 4){return '未发货';}else if(d.shipping_status === 5){return '已发货';}else if(d.shipping_status === 6){return '已收货';}else if(d.shipping_status === 7){return '已退货';}else if(d.shipping_status === 8){return '到店自取';}else if(d.shipping_status === 9){return '发放账户';}}}
                    ,{field: 'pay_status', title: '支付状态', width: 90, templet: function(d){if(d.pay_status === 1){return '未付款';}else if(d.pay_status === 2){return '付款中';}else if(d.pay_status === 3){return '已付款';}}}
                    ,{field: 'created_at', title: '下单时间', sort: true, width:170}
                    ,{fixed: 'right', title:'操作', minWidth: 150, toolbar: '#barDemo'}
                ]]
            });
            $("#screen").click(function(){
                tableIns.reload({
                    where: {
                        order_sn: $("input[name='order_sn']").val()
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
                if(layEvent === 'show'){
                    window.location.href = "/giftcard_order/"+data.id;
                }else if(layEvent === 'print'){
                    $("#print").jqprint({
                        debug: false,
                        importCSS: true,
                        printContainer: true,
                        operaSupport: false
                    });
                }
            });
        });
    </script>
@endsection