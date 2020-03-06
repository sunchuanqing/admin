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
                <a><cite>售后列表</cite></a>
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
                                <input type="text" name="after_sale_sn" placeholder="服务单号" autocomplete="off" class="layui-input">
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
        <a class="layui-btn layui-btn-xs" lay-event="show">查看详情</a>
    </script>
    <script>
        layui.use('table', function(){
            var table = layui.table;
            var tableIns = table.render({
                elem: '#list'
                ,url: '/after_sale'
                ,height: 'full-270'
                ,page: {
                    layout: ['prev', 'page', 'next', 'count', 'skip']
                    ,groups: 5
                }
                ,limit: 20
                ,cols: [[
                    {field: 'id', title: 'ID', width:80, sort: true}
//                    ,{field: 'after_sale_sn', title: '门店类型', width:150, templet: function(d){return d.shop_type.type_name}}
                    ,{field: 'after_sale_sn', title: '服务编号', width:190}
                    ,{field: 'after_sale_order_sn', title: '售后订单编号', width: 240}
                    ,{field: 'goods_name', title: '商品信息', width: 270}
                    ,{field: 'after_sale_order_goods_number', title: '数量', width: 80}
                    ,{field: 'after_sale_type_name', title: '售后类别', width: 120}
                    ,{field: 'after_sale_describe', title: '售后描述', width: 200}
                    ,{field: 'status', title: '状态', width:100, templet: function(d){if(d.status === 1){return '审核中';}else if(d.status === 2){return '处理中';}else if(d.status === 3){return '处理完毕';}else if(d.status === 4){return '取消申请';}}}
                    ,{fixed: 'right', title:'操作', toolbar: '#barDemo'}
                ]]
            });
            $("#screen").click(function(){
                tableIns.reload({
                    where: {
                        after_sale_sn: $("input[name='after_sale_sn']").val()
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
                    window.location.href = "/after_sale/"+data.id;
                }
            });
        });
    </script>
@endsection