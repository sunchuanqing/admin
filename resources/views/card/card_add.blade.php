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
                <a href="/card">卡片列表</a>
                <a><cite>添加卡片</cite></a>
            </span>
        </div>
        <div class="add">
            <form class="layui-form" action="">
                <div class="layui-form-item">
                    <label class="layui-form-label">卡片名称：</label>
                    <div class="layui-input-block">
                        <input type="text" name="card_name" required  lay-verify="required" placeholder="卡片名称" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">卡片小图：</label>
                    <div class="layui-input-block">
                        <button type="button" class="layui-btn" id="cardimg">
                            <i class="layui-icon">&#xe67c;</i>上传图片
                        </button>
                        <div class="layui-upload-list">
                            <img id="cardimgs" style="width: 200px; height: 110px;" src="/image/bj.png" alt="">
                            <input type="hidden" name="card_img" lay-verify="required" value="" id="card_img">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">卡片大图：</label>
                    <div class="layui-input-block">
                        <button type="button" class="layui-btn" id="cardwholeimg">
                            <i class="layui-icon">&#xe67c;</i>上传图片
                        </button>
                        <div class="layui-upload-list">
                            <img id="cardwholeimgs" style="width: 200px; height: 110px;" src="/image/bj.png" alt="">
                            <input type="hidden" name="card_whole_img" lay-verify="required" value="" id="card_whole_img">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">价格：</label>
                        <div class="layui-input-inline" style="width: 274px;">
                            <input type="text" name="price" lay-verify="required" placeholder="价格" autocomplete="off" class="layui-input">
                        </div>
                        <div class="layui-form-mid">会员价：</div>
                        <div class="layui-input-inline" style="width: 274px;">
                            <input type="text" name="vip_price" lay-verify="required" placeholder="会员价" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">库存：</label>
                    <div class="layui-input-block">
                        <input type="text" name="card_number" required  lay-verify="required" placeholder="库存" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">特权说明：</label>
                    <div class="layui-input-block">
                        <input type="text" name="card_brief" required  lay-verify="required" placeholder="特权说明" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">有效天数：</label>
                    <div class="layui-input-block">
                        <input type="text" name="card_period" required  lay-verify="required" placeholder="有效天数" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">联系电话：</label>
                    <div class="layui-input-block">
                        <input type="text" name="phone" required  lay-verify="required" placeholder="联系电话" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">状态：</label>
                    <div class="layui-input-block">
                        <input type="radio" name="status" value="1" title="在售" checked>
                        <input type="radio" name="status" value="2" title="下架">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">适用门店：</label>
                    <div class="layui-input-block">
                        <input type="text" name="shop_id" required  lay-verify="required" placeholder="适用门店（门店 id 用,隔开）" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">使用须知：</label>
                    <div class="layui-input-block">
                        <textarea name="card_notice" placeholder="使用须知" class="layui-textarea"></textarea>
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
        layui.use('upload', function(){
            var upload = layui.upload;
            //执行实例
            var uploadInst = upload.render({
                elem: '#cardimg'
                ,url: '/qiniu'
                ,accept: 'images'
                ,size: '2048'
                ,data: {'_token': '{{csrf_token()}}'}
                ,before: function(obj){
                    layer.msg('图片上传中', {icon: 16, shade: [0.1, '#000000'], time: 0});
                }
                ,done: function(res){
                    $("#cardimgs").attr('src', res.data.src);
                    $("#card_img").val(res.data.src);
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
                elem: '#cardwholeimg'
                ,url: '/qiniu'
                ,accept: 'images'
                ,size: '2048'
                ,data: {'_token': '{{csrf_token()}}'}
                ,before: function(obj){
                    layer.msg('图片上传中', {icon: 16, shade: [0.1, '#000000'], time: 0});
                }
                ,done: function(res){
                    $("#cardwholeimgs").attr('src', res.data.src);
                    $("#card_whole_img").val(res.data.src);
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
                    type: 'post',
                    url: '/card',
                    data: {'_token': '{{csrf_token()}}', 'card_name': data.field.card_name, 'card_number': data.field.card_number, 'price': data.field.price, 'vip_price': data.field.vip_price, 'card_img': data.field.card_img, 'card_whole_img': data.field.card_whole_img, 'status': data.field.status, 'card_brief': data.field.card_brief, 'card_period': data.field.card_period, 'phone': data.field.phone, 'card_notice': data.field.card_notice, 'shop_id': data.field.shop_id},
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