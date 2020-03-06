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
                <a href="/user">会员列表</a>
                <a><cite>修改会员</cite></a>
            </span>
        </div>
        <div class="add">
            <form class="layui-form" action="">
                <input type="hidden" name="id" value="{{$data['id']}}">
                <div class="layui-form-item">
                    <label class="layui-form-label">用户名：</label>
                    <div class="layui-input-block">
                        <input type="text" name="user_name" value="{{$data['user_name']}}" required  lay-verify="required" placeholder="用户名" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">头像：</label>
                    <div class="layui-input-block">
                        <button type="button" class="layui-btn" id="photoimg">
                            <i class="layui-icon">&#xe67c;</i>上传头像
                        </button>
                        <div class="layui-upload-list">
                            <img id="photo_img" style="height: 150px;" src="{{$data['photo']}}" alt="">
                            <input type="hidden" name="photo" value="{{$data['photo']}}" id="photo">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">手机号：</label>
                    <div class="layui-input-block">
                        <input type="text" name="phone" value="{{$data['phone']}}" required  lay-verify="required|phone" placeholder="手机号" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">性别：</label>
                    <div class="layui-input-block">
                        <input type="radio" name="sex" value="1" title="男" {{$data['sex'] == 1 ? 'checked' : ''}}>
                        <input type="radio" name="sex" value="2" title="女" {{$data['sex'] == 2 ? 'checked' : ''}}>
                        <input type="radio" name="sex" value="0" title="保密" {{$data['sex'] == 0 ? 'checked' : ''}}>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">状态：</label>
                    <div class="layui-input-block">
                        <input type="radio" name="flag" value="1" title="启用" {{$data['flag'] == 1 ? 'checked' : ''}}>
                        <input type="radio" name="flag" value="2" title="禁用" {{$data['flag'] == 2 ? 'checked' : ''}}>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">出生日期：</label>
                    <div class="layui-input-block">
                        <input type="text" name="birthday" value="{{$data['birthday']}}" required id="birthday" placeholder="年-月-日" autocomplete="off" class="layui-input">
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
                elem: '#photoimg'
                ,url: '/photos'
                ,accept: 'images'
                ,size: '1024'
                ,data: {'_token': '{{csrf_token()}}'}
                ,before: function(obj){
                    layer.msg('图片上传中', {icon: 16, shade: [0.1, '#000000'], time: 0});
                }
                ,done: function(res){
                    $("#photo_img").attr('src', res.data.src);
                    $("#photo").val(res.data.src);
                    layer.closeAll();
                }
                ,error: function(){
                    layer.closeAll();
                    layer.msg('上传失败请重试', {icon: 5, anim: 6});
                }
            });
        });
        //Demo
        layui.use(['form', 'laydate'], function(){
            var form = layui.form
                ,laydate = layui.laydate;
            laydate.render({
                elem: '#birthday'
            });
            //监听提交
            form.on('submit(formDemo)', function(data){
                var index = layer.load(2, {shade: [0.1, '#000000']});
                $.ajax({
                    type: 'put',
                    url: '/user/'+data.field.id,
                    data: {'_token': '{{csrf_token()}}', 'user_name': data.field.user_name, 'phone': data.field.phone, 'photo': data.field.photo, 'sex': data.field.sex, 'flag': data.field.flag, 'birthday': data.field.birthday},
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