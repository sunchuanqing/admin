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
                <a href="/coupon">优惠券列表</a>
                <a><cite>修改优惠券</cite></a>
            </span>
        </div>
        <div class="add">
            <form class="layui-form" action="">
                <input type="hidden" name="id" value="{{$data['id']}}">
                <div class="layui-form-item">
                    <label class="layui-form-label">优惠券：</label>
                    <div class="layui-input-block">
                        <input type="text" name="name" value="{{$data['name']}}" required  lay-verify="required" placeholder="优惠券名称" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">样式图片：</label>
                    <div class="layui-input-block">
                        <button type="button" class="layui-btn" id="imgurl">
                            <i class="layui-icon">&#xe67c;</i>上传图片
                        </button>
                        <span id="beizhu" style="color: #666;"></span>
                        <div class="layui-upload-list">
                            <img id="imgurls" style="width: 150px; height: 60px;" src="{{$data['img']}}" alt="">
                            <input type="hidden" name="img" value="{{$data['img']}}" lay-verify="required" value="" id="img">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">发放时间：</label>
                        <div class="layui-input-inline" style="width: 274px;">
                            <input type="text" name="start_time" value="{{$data['start_time']}}" id="start_time" placeholder="开始时间" lay-verify="required" autocomplete="off" class="layui-input">
                        </div>
                        <div class="layui-form-mid">-</div>
                        <div class="layui-input-inline" style="width: 274px;">
                            <input type="text" name="end_time" value="{{$data['end_time']}}" id="end_time" placeholder="结束时间" lay-verify="required" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">发放数量：</label>
                        <div class="layui-input-inline" style="width: 274px;">
                            <input type="text" name="number" value="{{$data['number']}}" lay-verify="required|number" placeholder="优惠券发放数量" autocomplete="off" class="layui-input">
                        </div>
                        <div class="layui-form-label">领取数量：</div>
                        <div class="layui-input-inline" style="width: 274px;">
                            <input type="text" name="user_number" value="{{$data['user_number']}}" lay-verify="required|number" placeholder="每位用户可领取最大的数量" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">付费金额：</label>
                    <div class="layui-input-block">
                        <input type="text" name="pay_money" value="{{$data['pay_money']}}" required  lay-verify="required|number" placeholder="0为免费类型 大于0为付费金额" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">券类别：</label>
                    <div class="layui-input-block">
                        <input type="radio" name="coupon_type" lay-filter="coupon_type" value="1" title="现金券" {{$data['coupon_type'] == 1 ? 'checked' : ''}}>
                        <input type="radio" name="coupon_type" lay-filter="coupon_type" value="2" title="满减券" {{$data['coupon_type'] == 2 ? 'checked' : ''}}>
                        <input type="radio" name="coupon_type" lay-filter="coupon_type" value="3" title="新人券" {{$data['coupon_type'] == 3 ? 'checked' : ''}}>
                        <input type="radio" name="coupon_type" lay-filter="coupon_type" value="4" title="积分兑换券" {{$data['coupon_type'] == 4 ? 'checked' : ''}}>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">优惠金额：</label>
                        <div class="layui-input-inline" style="width: 274px;">
                            <input type="text" name="money" value="{{$data['coupon_types']['money']}}" lay-verify="required|number" placeholder="优惠金额" autocomplete="off" class="layui-input">
                        </div>
                        <div class="layui-form-label">最小金额：</div>
                        <div class="layui-input-inline" style="width: 274px;">
                            <input type="text" name="full_money" value="{{$data['coupon_types']['full_money']}}" lay-verify="required|number" placeholder="使用优惠券的最低金额（0代表不限额）" autocomplete="off" class="layui-input">
                        </div>
                        {{--<label class="layui-form-label">折扣比率：</label>--}}
                        {{--<div class="layui-input-inline" style="width: 274px;">--}}
                        {{--<input type="text" name="discount" required  lay-verify="required|number" placeholder="折扣比率（例如 0.85 打85折）" autocomplete="off" class="layui-input">--}}
                        {{--</div>--}}
                        {{--<label class="layui-form-label">订单金额：</label>--}}
                        {{--<div class="layui-input-inline" style="width: 274px;">--}}
                        {{--<input type="text" name="order_money" required  lay-verify="required|number" placeholder="订单达到金额时赠送此券" autocomplete="off" class="layui-input">--}}
                        {{--</div>--}}
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">有效期：</label>
                    <div class="layui-input-block">
                        <input type="radio" name="valid_type" value="1" title="绝对时效" {{$data['valid_type'] == 1 ? 'checked' : ''}}>
                        <input type="radio" name="valid_type" value="2" title="相对时效" {{$data['valid_type'] == 2 ? 'checked' : ''}}>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">绝对时效：</label>
                        <div class="layui-input-inline" style="width: 274px;">
                            <input type="text" name="valid_start_time" value="{{$data['valid_start_time']}}" id="valid_start_time" lay-verify="required" placeholder="开始时间" autocomplete="off" class="layui-input">
                        </div>
                        <div class="layui-form-mid">-</div>
                        <div class="layui-input-inline" style="width: 274px;">
                            <input type="text" name="valid_end_time" value="{{$data['valid_end_time']}}" id="valid_end_time" lay-verify="required" placeholder="结束时间" autocomplete="off" class="layui-input">
                        </div>
                        <label class="layui-form-label">相对时效：</label>
                        <div class="layui-input-inline" style="width: 274px;">
                            <input type="text" name="valid_day" value="{{$data['valid_day']}}" required  lay-verify="required|number" placeholder="有效天数" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">使用主体：</label>
                    <div class="layui-input-block">
                        <input type="radio" name="subject_type" value="1" title="通用" {{$data['subject_type'] == 1 ? 'checked' : ''}}>
                        <input type="radio" name="subject_type" value="2" title="门店" {{$data['subject_type'] == 2 ? 'checked' : ''}}>
                        <input type="radio" name="subject_type" value="3" title="好货" {{$data['subject_type'] == 3 ? 'checked' : ''}}>
                        <input type="radio" name="subject_type" value="4" title="指定商品" {{$data['subject_type'] == 4 ? 'checked' : ''}}>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">发放类型：</label>
                    <div class="layui-input-block">
                        <input type="radio" name="grant_type" value="1" title="后台发放" {{$data['grant_type'] == 1 ? 'checked' : ''}}>
                        <input type="radio" name="grant_type" value="2" title="用户领取" {{$data['grant_type'] == 2 ? 'checked' : ''}}>
                        <input type="radio" name="grant_type" value="3" title="系统发放" {{$data['grant_type'] == 3 ? 'checked' : ''}}>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">适用说明：</label>
                    <div class="layui-input-block">
                        <textarea name="usable_range" placeholder="适用说明" class="layui-textarea">{{$data['usable_range']}}</textarea>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">其他说明：</label>
                    <div class="layui-input-block">
                        <textarea name="else_msg" placeholder="其他说明" class="layui-textarea">{{$data['else_msg']}}</textarea>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">退改说明：</label>
                    <div class="layui-input-block">
                        <textarea name="bc_msg" placeholder="退改说明" class="layui-textarea">{{$data['bc_msg']}}</textarea>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn" lay-submit lay-filter="formDemo">确定修改</button>
                        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        layui.use('upload', function(){
            var upload = layui.upload;
            //执行实例
            var uploadInst = upload.render({
                elem: '#imgurl'
                ,url: '/qiniu'
                ,accept: 'images'
                ,size: '1024'
                ,data: {'_token': '{{csrf_token()}}'}
                ,before: function(obj){
                    layer.msg('图片上传中', {icon: 16, shade: [0.1, '#000000'], time: 0});
                }
                ,done: function(res){
                    $("#imgurls").attr('src', res.data.src);
                    $("#img").val(res.data.src);
                    layer.closeAll();
                }
                ,error: function(){
                    layer.closeAll();
                    layer.msg('上传失败请重试', {icon: 5, anim: 6});
                }
            });
        });
        layui.use('laydate', function(){
            var laydate = layui.laydate;
            laydate.render({
                elem: '#start_time'
                ,type: 'datetime'
            });
            laydate.render({
                elem: '#end_time'
                ,type: 'datetime'
            });
            laydate.render({
                elem: '#valid_start_time'
                ,type: 'datetime'
            });
            laydate.render({
                elem: '#valid_end_time'
                ,type: 'datetime'
            });
        });
        //Demo
        layui.use('form', function(){
            var form = layui.form;
            //监听提交
            form.on('submit(formDemo)', function(data){
                var index = layer.load(2, {shade: [0.1, '#000000']});
                $.ajax({
                    type: 'put',
                    url: '/coupon/'+data.field.id,
                    data: {'_token': '{{csrf_token()}}', 'name': data.field.name, 'number': data.field.number, 'user_number': data.field.user_number, 'img': data.field.img, 'start_time': data.field.start_time, 'end_time': data.field.end_time, 'valid_type': data.field.valid_type, 'valid_start_time': data.field.valid_start_time, 'valid_end_time': data.field.valid_end_time, 'valid_day': data.field.valid_day, 'pay_money': data.field.pay_money, 'coupon_type': data.field.coupon_type, 'subject_type': data.field.subject_type, 'grant_type': data.field.grant_type, 'money': data.field.money, 'full_money': data.field.full_money, 'usable_range': data.field.usable_range, 'else_msg': data.field.else_msg, 'bc_msg': data.field.bc_msg},
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

            form.on('radio(coupon_type)', function(data){
                console.log(data.elem); //得到radio原始DOM对象
                console.log(data.value); //被点击的radio的value值
            });
        });
    </script>
@endsection