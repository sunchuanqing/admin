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
                <a href="/topic">活动列表</a>
                <a><cite>添加活动</cite></a>
            </span>
        </div>
        <div class="add">
            <form class="layui-form" action="">
                <div class="layui-form-item">
                    <label class="layui-form-label">活动名称：</label>
                    <div class="layui-input-block">
                        <input type="text" name="topic_name" required  lay-verify="required" placeholder="专题名称" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">活动图片：</label>
                    <div class="layui-input-block">
                        <button type="button" class="layui-btn" id="topicimg">
                            <i class="layui-icon">&#xe67c;</i>上传图片
                        </button>
                        <div class="layui-upload-list">
                            <img id="topicimgs" style="width: 200px;" src="/image/bj.png" alt="">
                            <input type="hidden" name="topic_img" lay-verify="required" value="" id="topic_img">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">详情图片：</label>
                    <div class="layui-input-block">
                        <button type="button" class="layui-btn" id="topicinfoimg">
                            <i class="layui-icon">&#xe67c;</i>上传图片
                        </button>
                        <div class="layui-upload-list">
                            <img id="topicinfoimgs" style="width: 200px;" src="/image/bj.png" alt="">
                            <input type="hidden" name="topic_info_img" lay-verify="required" value="" id="topic_info_img">
                        </div>
                    </div>
                </div>
                {{--<div class="layui-form-item">--}}
                    {{--<div class="layui-inline">--}}
                        {{--<label class="layui-form-label">有效时间：</label>--}}
                        {{--<div class="layui-input-inline" style="width: 274px;">--}}
                            {{--<input type="text" name="start_time" id="start_time" placeholder="开始时间" autocomplete="off" class="layui-input">--}}
                        {{--</div>--}}
                        {{--<div class="layui-form-mid">-</div>--}}
                        {{--<div class="layui-input-inline" style="width: 274px;">--}}
                            {{--<input type="text" name="end_time" id="end_time" placeholder="结束时间" autocomplete="off" class="layui-input">--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
                <div class="layui-form-item">
                    <label class="layui-form-label">排序：</label>
                    <div class="layui-input-block">
                        <input type="text" name="sort" required  lay-verify="required" placeholder="排序（1-999大号在前）" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">路由：</label>
                    <div class="layui-input-block">
                        <input type="text" name="route" required  lay-verify="required" placeholder="路由" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">商品类别：</label>
                    <div class="layui-input-block">
                        <input type="text" name="goods_type" required  lay-verify="required" placeholder="商品类别（1：卡片）" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">商品ID：</label>
                    <div class="layui-input-block">
                        <input type="text" name="goods_id" required  lay-verify="required" placeholder="商品id" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">是否显示：</label>
                    <div class="layui-input-block">
                        <input type="radio" name="status" value="1" title="展示" checked>
                        <input type="radio" name="status" value="2" title="隐藏">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">是否置顶：</label>
                    <div class="layui-input-block">
                        <input type="radio" name="is_top" value="1" title="否" checked>
                        <input type="radio" name="is_top" value="2" title="是">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">专题简介：</label>
                    <div class="layui-input-block">
                        <textarea name="topic_brief" placeholder="请输入内容" class="layui-textarea"></textarea>
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
                elem: '#topicimg'
                ,url: '/qiniu'
                ,accept: 'images'
                ,size: '2048'
                ,data: {'_token': '{{csrf_token()}}'}
                ,before: function(obj){
                    layer.msg('图片上传中', {icon: 16, shade: [0.1, '#000000'], time: 0});
                }
                ,done: function(res){
                    $("#topicimgs").attr('src', res.data.src);
                    $("#topic_img").val(res.data.src);
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
                elem: '#topicinfoimg'
                ,url: '/qiniu'
                ,accept: 'images'
                ,size: '2048'
                ,data: {'_token': '{{csrf_token()}}'}
                ,before: function(obj){
                    layer.msg('图片上传中', {icon: 16, shade: [0.1, '#000000'], time: 0});
                }
                ,done: function(res){
                    $("#topicinfoimgs").attr('src', res.data.src);
                    $("#topic_info_img").val(res.data.src);
                    layer.closeAll();
                }
                ,error: function(){
                    layer.closeAll();
                    layer.msg('上传失败请重试', {icon: 5, anim: 6});
                }
            });
        });
//        layui.use('laydate', function(){
//            var laydate = layui.laydate;
//            laydate.render({
//                elem: '#start_time'
//                ,type: 'date'
//            });
//            laydate.render({
//                elem: '#end_time'
//                ,type: 'date'
//            });
//        });
        //Demo
        layui.use('form', function(){
            var form = layui.form;
            //监听提交
            form.on('submit(formDemo)', function(data){
                var index = layer.load(2, {shade: [0.1, '#000000']});
                $.ajax({
                    type: 'post',
                    url: '/topic',
                    data: {'_token': '{{csrf_token()}}', 'topic_name': data.field.topic_name, 'topic_info_img': data.field.topic_info_img, 'topic_img': data.field.topic_img, 'sort': data.field.sort, 'status': data.field.status, 'is_top': data.field.is_top, 'topic_brief': data.field.topic_brief, 'goods_type': data.field.goods_type, 'goods_id': data.field.goods_id, 'route': data.field.route},
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