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
                <a href="/order">订单列表</a>
                <a><cite>订单详情</cite></a>
            </span>
        </div>
        <div class="admin-table">
            <div style="padding: 20px; background-color: #F2F2F2;">
                <div class="layui-row layui-col-space15">
                    <div class="layui-col-md6">
                        <div class="layui-card">
                            <div class="layui-card-header">基本信息</div>
                            <div class="layui-card-body">
                                订单编号：{{$data->order_sn}}<br>
                                订单类别：@if ($data->order_type === 1)奢饰品护理@elseif ($data->order_type === 2)名车护理@elseif ($data->order_type === 3)花艺@elseif ($data->order_type === 4)优惠券@elseif ($data->order_type === 5)优惠服务@elseif ($data->order_type === 6)好货@endif<br>
                                订单状态：@if ($data->order_status === 1)已预约@elseif ($data->order_status === 2)洗护中@elseif ($data->order_status === 3)洗护完工@elseif ($data->order_status === 4)已完成@elseif ($data->order_status === 5)已取消@elseif ($data->order_status === 6)已下单@elseif ($data->order_status === 7)制作中@elseif ($data->order_status === 8)制作完成@elseif ($data->order_status === 9)已过期@endif<br>
                                配送状态：@if ($data->shipping_status === 1)未揽件@elseif ($data->shipping_status === 2)已揽件@elseif ($data->shipping_status === 3)已接收@elseif ($data->shipping_status === 4)未发货@elseif ($data->shipping_status === 5)已发货@elseif ($data->shipping_status === 6)已收货@elseif ($data->shipping_status === 7)已退货@elseif ($data->shipping_status === 8)门店自取@elseif ($data->shipping_status === 9)发放账户@endif<br>
                                支付状态：@if ($data->pay_status === 1)未付款@elseif ($data->pay_status === 2)付款中@elseif ($data->pay_status === 3)已付款@endif<br>
                                支付方式：{{$data->pay_name}}<br>
                                下单时间：{{$data->created_at}}<br>
                                支付时间：{{$data->pay_time}}<br>
                            </div>
                        </div>
                    </div>
                    <div class="layui-col-md6">
                        <div class="layui-card">
                            <div class="layui-card-header">收货人信息</div>
                            <div class="layui-card-body">
                                下单账户：{{$data->user_name}}（ID：{{$data->user_id}}）<br>
                                收货人：{{$data->consignee}}<br>
                                联系电话：{{$data->phone}}<br>
                                邮政编码：{{$data->zipcode}}<br>
                                配送区域：@if($data->province){{$address[$data->country][$data->province]}},@if ($address[$data->province][$data->city] != '市辖区'){{$address[$data->province][$data->city]}},@endif{{$address[$data->city][$data->district]}}@endif<br>
                                详细地址：{{$data->address}}<br>
                                最佳送货时间：{{$data->best_time}}<br>
                                买家留言：{{$data->postscript}}<br>
                            </div>
                        </div>
                    </div>
                    <div class="layui-col-md12">
                        <div class="layui-card">
                            <div class="layui-card-header">购买商品信息</div>
                            <div class="layui-card-body">
                                <table class="layui-table">
                                    <colgroup>
                                        <col width="200">
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
                                        <col width="200">
                                        <col width="200">
                                        <col width="200">
                                        <col width="100">
                                        <col width="100">
                                        <col width="100">
                                    </colgroup>
                                    <tbody>
                                    @foreach($data->order_goods as $k => $v)
                                        <tr>
                                            <td>{{$v->goods_name}}</td>
                                            <td>{{$v->goods_sn}}</td>
                                            <td>{{$v->attr_name}}</td>
                                            <td>¥ {{$v->make_price}}</td>
                                            <td>{{$v->goods_number}}</td>
                                            <td>¥ {{$v->make_price * $v->goods_number}}.00</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <table class="layui-table">
                                    <colgroup>
                                        <col width="800">
                                        <col width="100">
                                    </colgroup>
                                    <tbody>
                                    <tr>
                                        <td>实际付款 = 合计：¥{{$data->goods_amount-$data->shipping_fee}}.00 + 配送费：¥{{$data->shipping_fee}} - 礼品卡抵扣金额：¥{{$data->gift_card}} - 积分抵扣金额：¥{{$data->pay_points_money}} - 优惠券抵扣金额：¥{{$data->coupon}} - 优惠套餐抵扣金额：¥{{$data->server}} = ¥{{$data->order_amount}}</td>
                                        <td>合计：¥{{$data->goods_amount-$data->shipping_fee}}.00</td>
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
                                <table class="layui-table">
                                    <colgroup>
                                        <col width="200">
                                        <col width="200">
                                        <col width="100">
                                        <col width="100">
                                        <col width="100">
                                        <col width="300">
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
                                        <col width="300">
                                    </colgroup>
                                    <tbody id="table">
                                    @foreach($data->order_action as $k => $v)
                                        <tr id="index-1">
                                            <td>{{$v->action_user}}</td>
                                            <td>{{$v->created_at}}</td>
                                            <td>@if ($v->order_status === 1)已预约@elseif ($v->order_status === 2)洗护中@elseif ($v->order_status === 3)洗护完工@elseif ($v->order_status === 4)已完成@elseif ($v->order_status === 5)已取消@elseif ($v->order_status === 6)已下单@elseif ($v->order_status === 7)制作中@elseif ($v->order_status === 8)制作完成@elseif ($v->order_status === 9)已过期@endif
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
@endsection