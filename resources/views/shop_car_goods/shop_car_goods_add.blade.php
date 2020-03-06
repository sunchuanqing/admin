@extends('index')
@section('content')
    <style>
        .admin-overall{width: 100%; height: 100%;}
        .admin-nav{height: 50px; background-color: #ffffff; line-height: 50px; font-size: 14px; padding-left: 15px;}
        .add{margin: 15px; padding: 15px; background-color: #ffffff; border-radius: 5px; height: calc(100% - 105px); overflow-y: auto;}
    </style>
    <div class="admin-overall">
        <div class="admin-nav">
            <span class="layui-breadcrumb">
                <a href="/">主页</a>
                <a href="/shop_car_goods">车护商品列表</a>
                <a><cite>添加商品</cite></a>
            </span>
        </div>
        <div class="add">
            <form class="layui-form" action="">
                <div class="layui-form-item">
                    <label class="layui-form-label">选择门店：</label>
                    <div class="layui-input-block">
                        <select name="shop_id" lay-verify="required">
                            <option value=""></option>
                            @foreach($shop as $k => $v)
                                <option value="{{$v->id}}">{{$v->shop_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">商品名称：</label>
                    <div class="layui-input-block">
                        <input type="text" name="price_list_name" required  lay-verify="required" placeholder="商品名称" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">商品价格：</label>
                    <div class="layui-input-block">
                        <input type="text" name="price" required  lay-verify="required|number" placeholder="商品价格" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">销售提成：</label>
                    <div class="layui-input-block">
                        <input type="text" name="sell_money" required  lay-verify="required|number" placeholder="销售提成" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">施工提成：</label>
                    <div class="layui-input-block">
                        <input type="text" name="job_money" required  lay-verify="required|number" placeholder="施工提成" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn" lay-submit lay-filter="formDemo">确定添加</button>
                        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        //Demo
        layui.use('form', function(){
            var form = layui.form;
            //监听提交
            form.on('submit(formDemo)', function(data){
                var index = layer.load(2, {shade: [0.1, '#000000']});
                $.ajax({
                    type: 'post',
                    url: '/shop_car_goods',
                    data: {'_token': '{{csrf_token()}}', 'shop_id': data.field.shop_id, 'price_list_name': data.field.price_list_name, 'price': data.field.price, 'sell_money': data.field.sell_money, 'job_money': data.field.job_money},
                    dataType: 'json',
                    success: function(data){
                        layer.close(index);
                        if(data.code === 200){
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
                return false;
            });
        });
    </script>
@endsection