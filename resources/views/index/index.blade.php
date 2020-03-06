@extends('index')
@section('content')
    <div style="padding: 20px; background-color: #F2F2F2;">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md3">
                <div class="layui-card">
                    <div class="layui-card-header">
                        营业额
                        <span class="layui-badge layui-bg-blue" style="float: right; margin-top: 12px;">{{$data['month']}}月</span>
                    </div>
                    <div class="layui-card-body">
                        <p style="font-size: 36px; padding: 15px 0 0 0; color: #666;">{{$data['order_amounts']}}</p><br>
                        <p>
                            总营业额
                            <span style="float: right;">{{$data['order_amount']}} 元</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="layui-col-md3">
                <div class="layui-card">
                    <div class="layui-card-header">
                        实收金额
                        <span class="layui-badge layui-bg-blue" style="float: right; margin-top: 12px;">{{$data['month']}}月</span>
                    </div>
                    <div class="layui-card-body">
                        <p style="font-size: 36px; padding: 15px 0 0 0; color: #666;">{{$data['order_amounts']}}</p><br>
                        <p>
                            总实收金额
                            <span style="float: right;">{{$data['order_amount']}} 元</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="layui-col-md3">
                <div class="layui-card">
                    <div class="layui-card-header">
                        年卡销售金额
                        <span class="layui-badge layui-bg-blue" style="float: right; margin-top: 12px;">{{$data['month']}}月</span>
                    </div>
                    <div class="layui-card-body">
                        <p style="font-size: 36px; padding: 15px 0 0 0; color: #666;">{{$data['card_month_money']}}</p><br>
                        <p>
                            总金额
                            <span style="float: right;">{{$data['card_money']}} 元</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="layui-col-md3">
                <div class="layui-card">
                    <div class="layui-card-header">
                        订单量
                        <span class="layui-badge layui-bg-blue" style="float: right; margin-top: 12px;">{{$data['month']}}月</span>
                    </div>
                    <div class="layui-card-body">
                        <p style="font-size: 36px; padding: 15px 0 0 0; color: #666;">{{$data['order_month_sum']}}</p><br>
                        <p>
                            总订单量
                            <span style="float: right;">{{$data['order_sum']}} 笔</span>
                        </p>
                    </div>
                </div>
            </div>
            <input type="hidden" id="flower_order_money" value="{{$data['flower_order_money']}}">
            <input type="hidden" id="car_order_money" value="{{$data['car_order_money']}}">
            <input type="hidden" id="luxury_order_money" value="{{$data['luxury_order_money']}}">
            <input type="hidden" id="rests_order_money" value="{{$data['rests_order_money']}}">
            <div class="layui-col-md9">
                <div class="layui-card">
                    <div class="layui-card-header">
                        消费统计
                        <span class="layui-badge layui-bg-blue" style="float: right; margin-top: 12px;">2019年</span>
                    </div>
                    <div class="layui-card-body">
                        <div id="main" style="height:252px;"></div>
                        <script>
                            // 绘制图表。
                            echarts.init(document.getElementById('main')).setOption({
                                tooltip: {
                                    trigger: 'axis'
                                },
                                legend: {
                                    data:['花艺','车护','奢护','其他']
                                },
                                grid: {
                                    left: '3%',
                                    right: '4%',
                                    bottom: '3%',
                                    containLabel: true
                                },
                                xAxis: {
                                    type: 'category',
                                    boundaryGap: false,
                                    data: ['一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月']
                                },
                                yAxis: {
                                    type: 'value'
                                },
                                series: [
                                    {
                                        name:'花艺',
                                        type:'line',
                                        data:JSON.parse($("#flower_order_money").val())
                                    },
                                    {
                                        name:'车护',
                                        type:'line',
                                        data:JSON.parse($("#car_order_money").val())
                                    },
                                    {
                                        name:'奢护',
                                        type:'line',
                                        data:JSON.parse($("#luxury_order_money").val())
                                    },
                                    {
                                        name:'其他',
                                        type:'line',
                                        data:JSON.parse($("#rests_order_money").val())
                                    }
                                ]
                            });
                        </script>
                    </div>
                </div>
            </div>
            <div class="layui-col-md3">
                <div class="layui-card">
                    <div class="layui-card-header">
                        新增用户
                        <span class="layui-badge layui-bg-blue" style="float: right; margin-top: 12px;">{{$data['month']}}月</span>
                    </div>
                    <div class="layui-card-body">
                        <p style="font-size: 36px; padding: 15px 0 0 0; color: #666;">{{$data['user_month_sum']}}</p><br>
                        <p>
                            用户总量
                            <span style="float: right;">{{$data['user_sum']}} 位</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="layui-col-md3">
                <div class="layui-card">
                    <div class="layui-card-header">
                        余额充值金额
                        <span class="layui-badge layui-bg-blue" style="float: right; margin-top: 12px;">{{$data['month']}}月</span>
                    </div>
                    <div class="layui-card-body">
                        <p style="font-size: 36px; padding: 15px 0 0 0; color: #666;">{{$data['user_month_sum']}}</p><br>
                        <p>
                            用户总量
                            <span style="float: right;">{{$data['user_sum']}} 元</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection