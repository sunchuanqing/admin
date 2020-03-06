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
                <a href="/goods">商品列表</a>
                <a href="/goods_attribute?goods_id={{$data['goods_id']}}">商品属性</a>
                <a><cite>修改属性</cite></a>
            </span>
        </div>
        <div class="add">
            <form class="layui-form" action="">
                <input type="hidden" name="id" value="{{$data['id']}}">
                <div class="layui-form-item">
                    <label class="layui-form-label">属性名称：</label>
                    <div class="layui-input-block">
                        <input type="text" name="attr_name" value="{{$data['attr_name']}}" required  lay-verify="required" placeholder="属性名称（例如：xxl 白色）" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">属性加价：</label>
                    <div class="layui-input-block">
                        <input type="text" name="attr_money" value="{{$data['attr_money']}}" required  lay-verify="required|number" placeholder="属性加价（大于等于0）" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">属性库存：</label>
                    <div class="layui-input-block">
                        <input type="text" name="attr_number" value="{{$data['attr_number']}}" required  lay-verify="required|number" placeholder="属性库存（大于等于0）" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">属性图片：</label>
                    <div class="layui-input-block">
                        <button type="button" class="layui-btn" id="attrimg">
                            <i class="layui-icon">&#xe67c;</i>上传图片
                        </button>
                        <div class="layui-upload-list">
                            <img id="attrimgs" style="width: 210px; height: 110px;" src="{{$data['attr_img']}}" alt="">
                            <input type="hidden" name="attr_img" value="{{$data['attr_original_img']}}" lay-verify="required" id="attr_img">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">属性排序：</label>
                    <div class="layui-input-block">
                        <input type="text" name="attr_sort" value="{{$data['attr_sort']}}" required  lay-verify="required|number" placeholder="属性排序（1-999 大号在前）" autocomplete="off" class="layui-input">
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
                elem: '#attrimg'
                ,url: '/qiniu'
                ,accept: 'images'
                ,size: '2048'
                ,data: {'_token': '{{csrf_token()}}'}
                ,before: function(obj){
                    layer.msg('图片上传中', {icon: 16, shade: [0.1, '#000000'], time: 0});
                }
                ,done: function(res){
                    $("#attrimgs").attr('src', res.data.src);
                    $("#attr_img").val(res.data.src);
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
                    url: '/goods_attribute/'+data.field.id,
                    data: {'_token': '{{csrf_token()}}', 'attr_name': data.field.attr_name, 'attr_money': data.field.attr_money, 'attr_number': data.field.attr_number, 'attr_img': data.field.attr_img, 'attr_sort': data.field.attr_sort},
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