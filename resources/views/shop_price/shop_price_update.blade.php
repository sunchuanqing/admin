@extends('index')
@section('content')
    <style>
        .admin-overall{width: 100%; height: 100%;}
        .admin-nav{height: 50px; background-color: #ffffff; line-height: 50px; font-size: 14px; padding-left: 15px;}
        .add{margin: 15px; padding: 15px; background-color: #ffffff; border-radius: 5px; height: calc(100% - 105px);}
    </style>
    <div class="admin-overall">
        <div class="admin-nav">
            <span class="layui-breadcrumb">
                <a href="/">主页</a>
                <a href="/shop">门店列表</a>
                <a href="/shop_price/{{$data['shop_id']}}">价目表类别</a>
                <a><cite>修改价目表类别</cite></a>
            </span>
        </div>
        <div class="add">
            <form class="layui-form" action="">
                <input type="hidden" name="id" value="{{$data['id']}}">
                {{--<div class="layui-form-item">--}}
                    {{--<label class="layui-form-label">类别名称：</label>--}}
                    {{--<div class="layui-input-block">--}}
                        {{--<input type="text" name="serve_type_name" value="{{$data['serve_type_name']}}" required  lay-verify="required" placeholder="类别名称" autocomplete="off" class="layui-input">--}}
                    {{--</div>--}}
                {{--</div>--}}
                <div class="layui-form-item">
                    <label class="layui-form-label">分类名称：</label>
                    <div class="layui-input-block">
                        <input type="text" name="name" value="{{$data['name']}}" required  lay-verify="required" placeholder="请输入分类名称" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">图片：</label>
                    <div class="layui-input-block">
                        <button type="button" class="layui-btn" id="imgurl">
                            <i class="layui-icon">&#xe67c;</i>上传图片
                        </button>
                        <div class="layui-upload-list">
                            <img id="imgurls" src="{{$data['img']}}" style="height: 150px;" alt="">
                            <input type="hidden" name="img_url" value="{{$data['img']}}" id="img_url">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">简介：</label>
                    <div class="layui-input-block">
                        <input type="text" name="brief" value="{{$data['brief']}}" placeholder="简介" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">价格说明：</label>
                    <div class="layui-input-block">
                        <input type="text" name="price_info" value="{{$data['price_info']}}" placeholder="价格说明" autocomplete="off" class="layui-input">
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
                    $("#img_url").val(res.data.src);
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
                    url: '/shop_price/'+data.field.id,
                    data: {'_token': '{{csrf_token()}}', 'name': data.field.name, 'img': data.field.img_url, 'brief': data.field.brief, 'price_info': data.field.price_info},
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