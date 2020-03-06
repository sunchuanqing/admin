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
                <a href="/goods">门店列表</a>
                <a href="/goods_info?goods_id={{$goods_id}}">好货图片详情</a>
                <a><cite>添加图片</cite></a>
            </span>
        </div>
        <div class="add">
            <form class="layui-form" action="">
                <input type="hidden" name="goods_id" value="{{$goods_id}}">
                <div class="layui-form-item">
                    <label class="layui-form-label">商品图片：</label>
                    <div class="layui-input-block">
                        <button type="button" class="layui-btn" id="goodsinfo">
                            <i class="layui-icon">&#xe67c;</i>上传图片
                        </button>
                        <div class="layui-upload-list">
                            <img id="goodsinfos" style="width: 300px; height: 160px;" src="/image/bj.png" alt="">
                            <input type="hidden" name="goods_info" lay-verify="required" value="" id="goods_info">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">图片排序：</label>
                    <div class="layui-input-block">
                        <input type="text" name="sort" required  lay-verify="required" placeholder="图片排序（小号在前）" autocomplete="off" class="layui-input">
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
                elem: '#goodsinfo'
                ,url: '/qiniu'
                ,accept: 'images'
                ,size: '2048'
                ,data: {'_token': '{{csrf_token()}}'}
                ,before: function(obj){
                    layer.msg('图片上传中', {icon: 16, shade: [0.1, '#000000'], time: 0});
                }
                ,done: function(res){
                    $("#goodsinfos").attr('src', res.data.src);
                    $("#goods_info").val(res.data.src);
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
                    url: '/goods_info',
                    data: {'_token': '{{csrf_token()}}', 'goods_id': data.field.goods_id, 'goods_info': data.field.goods_info, 'sort': data.field.sort},
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