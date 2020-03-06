@extends('index')
@section('content')
    <style>
        .admin-overall{width: 100%; height: 100%;}
        .admin-nav{height: 50px; background-color: #ffffff; line-height: 50px; font-size: 14px; padding-left: 15px;}
        .admin-table{margin: 15px; padding: 15px 15px 15px 15px; background-color: #ffffff; border-radius: 5px; height: calc(100% - 110px); overflow-y: auto;}
        .layui-card-body .layui-table{margin: 0; margin-bottom: -1px;}
    </style>
    <div class="admin-overall">
        <div class="admin-nav">
            <span class="layui-breadcrumb">
                <a href="/">主页</a>
                <a href="/after_sale">售后列表</a>
                <a><cite>售后详情</cite></a>
            </span>
        </div>
        <div class="admin-table">
            <div style="padding: 20px; background-color: #F2F2F2;">
                <div class="layui-row layui-col-space15">
                    <div class="layui-col-md5">
                        <div class="layui-card">
                            <div class="layui-card-header">订单信息</div>
                            <div class="layui-card-body">
                                订单编号：{{$data->order_sn}}<br>
                                订单类别：@if ($data->order_type === 1)奢饰品护理@elseif ($data->order_type === 2)名车护理@elseif ($data->order_type === 3)花艺@elseif ($data->order_type === 4)优惠券@elseif ($data->order_type === 5)优惠服务@elseif ($data->order_type === 6)好货@endif<br>
                                订单状态：@if ($data->order_status === 1)已预约@elseif ($data->order_status === 2)洗护中@elseif ($data->order_status === 3)洗护完工@elseif ($data->order_status === 4)已完成@elseif ($data->order_status === 5)已取消@elseif ($data->order_status === 6)已下单@elseif ($data->order_status === 7)制作中@elseif ($data->order_status === 8)制作完成@endif<br>
                                配送状态：@if ($data->shipping_status === 1)未揽件@elseif ($data->shipping_status === 2)已揽件@elseif ($data->shipping_status === 3)已接收@elseif ($data->shipping_status === 4)未发货@elseif ($data->shipping_status === 5)已发货@elseif ($data->shipping_status === 6)已收货@elseif ($data->shipping_status === 7)已退货@elseif ($data->shipping_status === 8)门店自取@elseif ($data->shipping_status === 9)发放账户@endif<br>
                                支付状态：@if ($data->pay_status === 1)未付款@elseif ($data->pay_status === 2)付款中@elseif ($data->pay_status === 3)已付款@endif<br>
                                支付方式：{{$data->pay_name}}<br>
                                下单时间：{{$data->created_at}}<br>
                                支付时间：{{$data->pay_time}}<br>
                            </div>
                        </div>
                    </div>
                    <div class="layui-col-md5">
                        <div class="layui-card">
                            <div class="layui-card-header">售后信息</div>
                            <div class="layui-card-body">
                                服务编号：{{$after_sale->after_sale_sn}}<br>
                                售后类别：{{$after_sale->after_sale_type_name}}<br>
                                服务状态：@if ($after_sale->status === 1)审核中@elseif ($after_sale->status === 2)处理中@elseif ($after_sale->status === 3)处理完毕@elseif ($after_sale->status === 4)取消申请@endif<br>
                                退款状态：@if ($after_sale->money_status === 1)未退款@elseif ($after_sale->money_status === 2)已退款@endif<br>
                                物流状态：@if ($after_sale->shipping_status === 1)未收货@elseif ($after_sale->shipping_status === 2)已收货@elseif ($after_sale->shipping_status === 3)已发货@endif<br>
                                问题描述：{{$after_sale->after_sale_describe}}<br>
                                寄回方式：@if ($after_sale->send_back_way === 1)邮寄到店@elseif ($after_sale->send_back_way === 2)送回到店@elseif ($after_sale->send_back_way === 3)无需寄回（虚拟物品）@endif<br>
                                快递信息：{{$after_sale->send_back_name}} {{$after_sale->send_back_sn}}<br>
                            </div>
                        </div>
                    </div>
                    <div class="layui-col-md2   ">
                        <div class="layui-card">
                            <div class="layui-card-header">售后图片</div>
                            <div class="layui-card-body img_demo" style="height: 192px;" id="img-demo">
                                @foreach($after_sale->img as $k => $v)
                                    <img layer-src="{{$v}}" src="{{$v}}" alt="" style="width: 70px; height: 70px; float: left; margin-right: 5px; margin-bottom: 5px; cursor: pointer;">
                                    @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="layui-col-md12">
                        <div class="layui-card">
                            <div class="layui-card-header">订单商品清单</div>
                            <div class="layui-card-body">
                                <table class="layui-table">
                                    <colgroup>
                                        <col width="300">
                                        <col width="200">
                                        <col width="200">
                                        <col width="100">
                                        <col width="100">
                                        <col width="100">
                                    </colgroup>
                                    <thead>
                                    <tr>
                                        <th>商品名称</th>
                                        <th>商品编号</th>
                                        <th>商品属性</th>
                                        <th>商品价格</th>
                                        <th>商品数量</th>
                                        <th>小计</th>
                                    </tr>
                                    </thead>
                                </table>
                                <table class="layui-table">
                                    <colgroup>
                                        <col width="300">
                                        <col width="200">
                                        <col width="200">
                                        <col width="100">
                                        <col width="100">
                                        <col width="100">
                                    </colgroup>
                                    <tbody>
                                    @foreach($data->order_goods as $k => $v)
                                        <tr>
                                            <td>{{$v->goods_name}} <span style="color: red;">@if ($v->after_sale_number >= 1)（申请售后物件 数量：{{$v->after_sale_number}}）@endif</span></td>
                                            <td>{{$v->goods_sn}}</td>
                                            <td>{{$v->attr_name}}</td>
                                            <td>¥ {{$v->make_price}}</td>
                                            <td>{{$v->goods_number}}</td>
                                            <td>¥ {{$v->make_price * $v->goods_number}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <table class="layui-table">
                                    <colgroup>
                                        <col width="900">
                                        <col width="100">
                                    </colgroup>
                                    <tbody>
                                    <tr>
                                        <td>实际付款 = 合计：¥{{$data->goods_amount}} + 配送费：¥{{$data->shipping_fee}} - 积分抵扣金额：¥{{$data->pay_points_money}} - 优惠券抵扣金额：¥{{$data->coupon}} - 优惠套餐抵扣金额：¥{{$data->server}} = ¥{{$data->order_amount}}</td>
                                        <td>合计：¥{{$data->goods_amount}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="layui-col-md12">
                        <div class="layui-card">
                            <div class="layui-card-header">操作信息</div>
                            <div class="layui-card-body">
                                <form class="layui-form" action="/orderdetails/{{$data->id}}" method="post">
                                    <input type="hidden" id="id" value="{{$data->id}}">
                                    <input type="hidden" id="order_sn" value="{{$data->order_sn}}">
                                    <input type="hidden" id="after_sale_id" value="{{$after_sale->id}}">
                                    <div class="layui-form-item layui-form-text">
                                        <div class="layui-input-block" style="margin-left: 0px;">
                                            <textarea id="after_sale_opinion" placeholder="备注内容" class="layui-textarea">{{$after_sale->after_sale_opinion}}</textarea>
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <div class="layui-input-block" style="margin-left: 0px;">
                                            @if ($after_sale->status === 1)
                                                <a href="javascript:passed();" class="layui-btn">通过审核</a>
                                                <a href="javascript:void(0);" class="layui-btn layui-btn-danger">拒绝售后</a>
                                            @elseif ($after_sale->status === 2)
                                                @if ($after_sale->shipping_status === 1)
                                                    @if ($after_sale->after_sale_type_id === 4)
                                                        <a href="javascript:rework();" class="layui-btn layui-btn-danger">生成返工单</a>
                                                    @elseif($after_sale->shipping_status === 2)
                                                        <a href="javascript:void(0);" class="layui-btn layui-btn-disabled">物品已寄回</a>
                                                        <a href="javascript:no_sign_for();" class="layui-btn layui-btn-danger">无需寄回</a>
                                                    @endif
                                                @elseif($after_sale->shipping_status === 2)
                                                    <a href="javascript:sign_for();" class="layui-btn layui-btn-danger">物品已寄回</a>
                                                @elseif($after_sale->shipping_status === 3)
                                                    @if ($after_sale->after_sale_type_id === 1)
                                                        <a href="javascript:swap_order();" class="layui-btn layui-btn-danger">生成换货单</a>
                                                    @elseif ($after_sale->after_sale_type_id === 2)
                                                        <a href="javascript:refund_money();" class="layui-btn layui-btn-danger">退款</a>
                                                    @elseif ($after_sale->after_sale_type_id === 3)
                                                        <a href="javascript:void(0);" class="layui-btn layui-btn-danger">维修完成</a>
                                                    @elseif ($after_sale->after_sale_type_id === 4)
                                                        <a href="javascript:rework();" class="layui-btn layui-btn-danger">生成返工单</a>
                                                    @elseif ($after_sale->after_sale_type_id === 5)
                                                        <a href="javascript:refund_money();" class="layui-btn">退款</a>
                                                    @endif
                                                @endif
                                            @elseif ($after_sale->status === 3)
                                                <a href="javascript:void(0);" class="layui-btn layui-btn-disabled">此售后已经处理完毕</a>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                                <table class="layui-table">
                                    <colgroup>
                                        <col width="200">
                                        <col width="200">
                                        <col width="100">
                                        <col width="100">
                                        <col width="100">
                                        <col width="400">
                                    </colgroup>
                                    <thead>
                                    <tr>
                                        <th>操作人员</th>
                                        <th>操作时间</th>
                                        <th>订单状态</th>
                                        <th>支付状态</th>
                                        <th>物流状态</th>
                                        <th>备注</th>
                                    </tr>
                                    </thead>
                                </table>
                                <table class="layui-table">
                                    <colgroup>
                                        <col width="200">
                                        <col width="200">
                                        <col width="100">
                                        <col width="100">
                                        <col width="100">
                                        <col width="400">
                                    </colgroup>
                                    <tbody id="table">
                                    @foreach($data->order_action as $k => $v)
                                        <tr id="index-1">
                                            <td>{{$v->action_user}}</td>
                                            <td>{{$v->created_at}}</td>
                                            <td>@if ($v->order_status === 1)已预约@elseif ($v->order_status === 2)洗护中@elseif ($v->order_status === 3)洗护完工@elseif ($v->order_status === 4)已完成@elseif ($v->order_status === 5)已取消@elseif ($v->order_status === 6)已下单@elseif ($v->order_status === 7)制作中@elseif ($v->order_status === 8)制作完成@endif
                                            </td>
                                            <td>@if ($v->pay_status === 1)未付款@elseif ($v->pay_status === 2)付款中@elseif ($v->pay_status === 3)已付款@endif</td>
                                            <td>@if ($v->shipping_status === 1)未揽件@elseif ($v->shipping_status === 2)已揽件@elseif ($v->shipping_status === 3)已接收@elseif ($v->shipping_status === 4)未发货@elseif ($v->shipping_status === 5)已发货@elseif ($v->shipping_status === 6)已收货@elseif ($v->shipping_status === 7)已退货@elseif ($v->shipping_status === 8)到店自取@elseif ($v->shipping_status === 9)发放账户@endif</td>
                                            <td>{{$v->action_note}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        layui.use(['layer', 'form', 'table'], function(){
            var layer = layui.layer;
            layer.photos({
                photos: '#img-demo'
                ,anim: 5
            });
        });

        // 审核通过
        function passed(){
            var layer = layui.layer;
            var index = layer.load(2, {shade: [0.1, '#000000']});
            $.ajax({
                type: 'post',
                url: '/passed',
                data: {'_token': '{{csrf_token()}}', 'after_sale_id': $("#after_sale_id").val(), 'after_sale_opinion': $("#after_sale_opinion").val()},
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
        }

        // 物件已寄回
        function sign_for(){
            var layer = layui.layer;
            var index = layer.load(2, {shade: [0.1, '#000000']});
            $.ajax({
                type: 'post',
                url: '/sign_for',
                data: {'_token': '{{csrf_token()}}', 'after_sale_id': $("#after_sale_id").val(), 'after_sale_opinion': $("#after_sale_opinion").val()},
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
        }

        // 物件无需寄回
        function no_sign_for(){
            var layer = layui.layer;
            var index = layer.load(2, {shade: [0.1, '#000000']});
            $.ajax({
                type: 'post',
                url: '/no_sign_for',
                data: {'_token': '{{csrf_token()}}', 'after_sale_id': $("#after_sale_id").val(), 'after_sale_opinion': $("#after_sale_opinion").val()},
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
        }

        // 退款
        function refund_money(){
            var layer = layui.layer;
            var index = layer.load(2, {shade: [0.1, '#000000']});
            $.ajax({
                type: 'post',
                url: '/refund_money',
                data: {'_token': '{{csrf_token()}}', 'after_sale_id': $("#after_sale_id").val(), 'after_sale_opinion': $("#after_sale_opinion").val()},
                dataType: 'json',
                success: function(data){
                    console.log(data);
//                    alert(data);
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
        }

        function swap_order(){
            var layer = layui.layer;
            layer.prompt({title: '请输入快递公司', formType: 0}, function(shipping_name, index){
                layer.close(index);
                layer.prompt({title: '请输入快递编号', formType: 0}, function(shipping_sn, indexs){
                    layer.close(indexs);
                    var index = layer.load(2, {shade: [0.1, '#000000']});
                    $.ajax({
                        type: 'post',
                        url: '/swap_order',
                        data: {'_token': '{{csrf_token()}}', 'after_sale_id': $("#after_sale_id").val(), 'to_buyer': $("#to_buyer").val(), 'shipping_name': shipping_name, 'shipping_sn': shipping_sn},
                        dataType: 'json',
                        success: function(data){
                            console.log(data);
                            layer.close(index);
                            if(data.code === 200){
                                layer.msg(data.msg, {icon: 1});
                                window.location.reload();
                            }else{
                                console.log(data);
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
        }

        // 生成返工单
        function rework(){
            var layer = layui.layer;
            layer.confirm('请确认物件已到门店', {
                btn: ['确认','取消']
            }, function(){
                var index = layer.load(2, {shade: [0.1, '#000000']});
                $.ajax({
                    type: 'post',
                    url: '/rework',
                    data: {'_token': '{{csrf_token()}}', 'after_sale_id': $("#after_sale_id").val(), 'after_sale_opinion': $("#after_sale_opinion").val()},
                    dataType: 'json',
                    success: function(data){
                        console.log(data);
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
            }, function(){

            });
        }
    </script>
@endsection