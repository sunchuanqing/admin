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
                <a href="/banner">轮播图列表</a>
                <a><cite>添加轮播图</cite></a>
            </span>
        </div>
        <div class="add">
            <form class="layui-form" action="">
                <div class="layui-form-item">
                    <label class="layui-form-label">轮播类别：</label>
                    <div class="layui-input-block">
                        <select name="type_id" lay-verify="required" lay-filter="banner_type">
                            <option value=""></option>
                            @foreach($banner_type as $k => $v)
                                <option value="{{$v->id}}.{{$v->banner_width}}.{{$v->banner_height}}">{{$v->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">轮播名称：</label>
                    <div class="layui-input-block">
                        <input type="text" name="name" required  lay-verify="required" placeholder="轮播图名称" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">轮播图片：</label>
                    <div class="layui-input-block">
                        <button type="button" class="layui-btn" id="imgurl">
                            <i class="layui-icon">&#xe67c;</i>上传图片
                        </button>
                        <span id="beizhu" style="color: #666;"></span>
                        <div class="layui-upload-list">
                            <img id="imgurls" src="/image/bj.png" alt="">
                            <input type="hidden" name="img_url" lay-verify="required" value="" id="img_url">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">跳转链接：</label>
                    <div class="layui-input-block">
                        <input type="text" name="link" required  lay-verify="required" placeholder="跳转链接" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">展示时间：</label>
                        <div class="layui-input-inline" style="width: 274px;">
                            <input type="text" name="start_time" id="start_time" placeholder="开始时间" autocomplete="off" class="layui-input">
                        </div>
                        <div class="layui-form-mid">-</div>
                        <div class="layui-input-inline" style="width: 274px;">
                            <input type="text" name="end_time" id="end_time" placeholder="结束时间" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">状态：</label>
                    <div class="layui-input-block">
                        <input type="radio" name="status" value="1" title="启用" checked>
                        <input type="radio" name="status" value="2" title="禁用">
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
        });
        //Demo
        layui.use('form', function(){
            var form = layui.form;
            form.on('select(banner_type)', function(data){
                var arr = data.value.split('.');
                var beizhu = '';
                type_id = '';
                for(var i in arr){
                    if(i == 1){
                        $("#imgurls").css("width", arr[i]/5+"px");
                        beizhu = beizhu+'宽度为 :'+arr[i]+'px; '
                    }else if(i == 2){
                        $("#imgurls").css("height", arr[i]/5+"px");
                        beizhu = beizhu+'高度为 :'+arr[i]+'px;'
                    }else{
                        type_id = arr[i];
                    }
                }
                $("#beizhu").html('图片尺寸 '+beizhu);
            });
            //监听提交
            form.on('submit(formDemo)', function(data){
                var index = layer.load(2, {shade: [0.1, '#000000']});
                $.ajax({
                    type: 'post',
                    url: '/banner',
                    data: {'_token': '{{csrf_token()}}', 'type_id': type_id, 'name': data.field.name, 'img_url': data.field.img_url, 'link': data.field.link, 'start_time': data.field.start_time, 'end_time': data.field.end_time, 'status': data.field.status},
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