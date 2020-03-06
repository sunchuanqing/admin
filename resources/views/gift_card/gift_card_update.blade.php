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
                <a href="/gift_card">卡片列表</a>
                <a><cite>修改卡片</cite></a>
            </span>
        </div>
        <div class="add">
            <form class="layui-form" action="">
                <input type="hidden" name="id" value="{{$data['id']}}">
                <div class="layui-form-item">
                    <label class="layui-form-label">类型：</label>
                    <div class="layui-input-block">
                        <input type="radio" name="gift_card_type" value="1" title="电子卡" {{$data['gift_card_type'] == 1 ? 'checked' : ''}}>
                        <input type="radio" name="gift_card_type" value="2" title="实体卡" {{$data['gift_card_type'] == 2 ? 'checked' : ''}}>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">卡片名称：</label>
                    <div class="layui-input-block">
                        <input type="text" name="gift_card_name" value="{{$data['gift_card_name']}}" required  lay-verify="required" placeholder="卡片名称" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">卡片正面：</label>
                    <div class="layui-input-block">
                        <button type="button" class="layui-btn" id="giftcardfrontimg">
                            <i class="layui-icon">&#xe67c;</i>上传图片
                        </button>
                        <div class="layui-upload-list">
                            <img id="giftcardfrontimgs" style="width: 200px; height: 110px;" src="{{$data['gift_card_front_img']}}" alt="">
                            <input type="hidden" name="gift_card_front_img" lay-verify="required" value="{{$data['gift_card_front_img']}}" id="gift_card_front_img">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">卡片反面：</label>
                    <div class="layui-input-block">
                        <button type="button" class="layui-btn" id="giftcardreverseimg">
                            <i class="layui-icon">&#xe67c;</i>上传图片
                        </button>
                        <div class="layui-upload-list">
                            <img id="giftcardreverseimgs" style="width: 200px; height: 110px;" src="{{$data['gift_card_reverse_img']}}" alt="">
                            <input type="hidden" name="gift_card_reverse_img" lay-verify="required" value="{{$data['gift_card_reverse_img']}}" id="gift_card_reverse_img">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">卡片详情：</label>
                    <div class="layui-input-block">
                        <button type="button" class="layui-btn" id="giftcardinfoimg">
                            <i class="layui-icon">&#xe67c;</i>上传图片
                        </button>
                        <div class="layui-upload-list">
                            <img id="giftcardinfoimgs" style="width: 200px; height: 110px;" src="{{$data['gift_card_info_img']}}" alt="">
                            <input type="hidden" name="gift_card_info_img" lay-verify="required" value="{{$data['gift_card_info_img']}}" id="gift_card_info_img">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">面值：</label>
                    <div class="layui-input-block">
                        <input type="text" name="gift_card_money" value="{{$data['gift_card_money']}}" required  lay-verify="required" placeholder="库存" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">价格：</label>
                        <div class="layui-input-inline" style="width: 274px;">
                            <input type="text" name="price" value="{{$data['price']}}" lay-verify="required" placeholder="价格" autocomplete="off" class="layui-input">
                        </div>
                        <div class="layui-form-mid">会员价：</div>
                        <div class="layui-input-inline" style="width: 274px;">
                            <input type="text" name="vip_price" value="{{$data['vip_price']}}" lay-verify="required" placeholder="会员价" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">库存：</label>
                    <div class="layui-input-block">
                        <input type="text" name="gift_card_number" value="{{$data['gift_card_number']}}" required  lay-verify="required" placeholder="库存" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">简介：</label>
                    <div class="layui-input-block">
                        <input type="text" name="gift_card_brief" value="{{$data['gift_card_brief']}}" required  lay-verify="required" placeholder="简介" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">有效天数：</label>
                    <div class="layui-input-block">
                        <input type="text" name="gift_card_period" value="{{$data['gift_card_period']}}" required  lay-verify="required" placeholder="有效天数" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">状态：</label>
                    <div class="layui-input-block">
                        <input type="radio" name="status" value="1" title="在售" {{$data['status'] == 1 ? 'checked' : ''}}>
                        <input type="radio" name="status" value="2" title="下架" {{$data['status'] == 2 ? 'checked' : ''}}>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">使用须知：</label>
                    <div class="layui-input-block">
                        <textarea name="gift_card_notice" placeholder="使用须知" class="layui-textarea">{{$data['gift_card_notice']}}</textarea>
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
                elem: '#giftcardfrontimg'
                ,url: '/qiniu'
                ,accept: 'images'
                ,size: '2048'
                ,data: {'_token': '{{csrf_token()}}'}
                ,before: function(obj){
                    layer.msg('图片上传中', {icon: 16, shade: [0.1, '#000000'], time: 0});
                }
                ,done: function(res){
                    $("#giftcardfrontimgs").attr('src', res.data.src);
                    $("#gift_card_front_img").val(res.data.src);
                    layer.closeAll();
                }
                ,error: function(){
                    layer.closeAll();
                    layer.msg('上传失败请重试', {icon: 5, anim: 6});
                }
            });
        });
        layui.use('upload', function(){
            var upload = layui.upload;
            //执行实例
            var uploadInst = upload.render({
                elem: '#giftcardreverseimg'
                ,url: '/qiniu'
                ,accept: 'images'
                ,size: '2048'
                ,data: {'_token': '{{csrf_token()}}'}
                ,before: function(obj){
                    layer.msg('图片上传中', {icon: 16, shade: [0.1, '#000000'], time: 0});
                }
                ,done: function(res){
                    $("#giftcardreverseimgs").attr('src', res.data.src);
                    $("#gift_card_reverse_img").val(res.data.src);
                    layer.closeAll();
                }
                ,error: function(){
                    layer.closeAll();
                    layer.msg('上传失败请重试', {icon: 5, anim: 6});
                }
            });
        });
        layui.use('upload', function(){
            var upload = layui.upload;
            //执行实例
            var uploadInst = upload.render({
                elem: '#giftcardinfoimg'
                ,url: '/qiniu'
                ,accept: 'images'
                ,size: '2048'
                ,data: {'_token': '{{csrf_token()}}'}
                ,before: function(obj){
                    layer.msg('图片上传中', {icon: 16, shade: [0.1, '#000000'], time: 0});
                }
                ,done: function(res){
                    $("#giftcardinfoimgs").attr('src', res.data.src);
                    $("#gift_card_info_img").val(res.data.src);
                    layer.closeAll();
                }
                ,error: function(){
                    layer.closeAll();
                    layer.msg('上传失败请重试', {icon: 5, anim: 6});
                }
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
                    url: '/gift_card/'+data.field.id,
                    data: {'_token': '{{csrf_token()}}', 'gift_card_name': data.field.gift_card_name, 'gift_card_number': data.field.gift_card_number, 'gift_card_type': data.field.gift_card_type, 'gift_card_money': data.field.gift_card_money, 'price': data.field.price, 'vip_price': data.field.vip_price, 'gift_card_brief': data.field.gift_card_brief, 'gift_card_front_img': data.field.gift_card_front_img, 'gift_card_reverse_img': data.field.gift_card_reverse_img, 'gift_card_info_img': data.field.gift_card_info_img, 'gift_card_period': data.field.gift_card_period, 'status': data.field.status, 'gift_card_notice': data.field.gift_card_notice},
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