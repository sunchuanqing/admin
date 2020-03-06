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
                <a href="/version">版本列表</a>
                <a><cite>添加版本</cite></a>
            </span>
        </div>
        <div class="add">
            <form class="layui-form" action="">
                <div class="layui-form-item">
                    <label class="layui-form-label">版本编号：</label>
                    <div class="layui-input-block">
                        <input type="text" name="version_number" required  lay-verify="required" placeholder="版本编号" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">版本包：</label>
                    <div class="layui-input-block">
                        <button type="button" class="layui-btn" id="versionurl">
                            <i class="layui-icon">&#xe67c;</i>上传文件
                        </button>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">版本地址：</label>
                    <div class="layui-input-block">
                        <input type="text" name="version_url" required  lay-verify="required" placeholder="版本地址" autocomplete="off" class="layui-input" id="version_url">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">版本信息：</label>
                    <div class="layui-input-block">
                        <input type="text" name="version_info" required  lay-verify="required" placeholder="版本信息" autocomplete="off" class="layui-input" id="version_url">
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
                elem: '#versionurl'
                ,url: '/qiniu'
                ,accept: 'file'
                ,size: '0'
                ,data: {'_token': '{{csrf_token()}}'}
                ,before: function(obj){
                    layer.msg('上传中', {icon: 16, shade: [0.1, '#000000'], time: 0});
                }
                ,done: function(res){
                    $("#version_url").val(res.data.src);
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
                    url: '/version',
                    data: {'_token': '{{csrf_token()}}', 'version_number': data.field.version_number, 'version_url': data.field.version_url, 'version_info': data.field.version_info},
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