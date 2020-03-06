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
                <a href="/admin">管理员列表</a>
                <a><cite>修改管理员</cite></a>
            </span>
        </div>
        <div class="add">
            <form class="layui-form" action="">
                <input type="hidden" name="id" value="{{$data['id']}}">
                <div class="layui-form-item">
                    <label class="layui-form-label">姓名：</label>
                    <div class="layui-input-block">
                        <input type="text" name="name" value="{{$data['name']}}" required  lay-verify="required|username" placeholder="姓名" autocomplete="off" class="layui-input">
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
                        <input type="radio" name="sex" value="3" title="保密" {{$data['sex'] == 3 ? 'checked' : ''}}>
                        <input type="radio" name="sex" value="1" title="男" {{$data['sex'] == 1 ? 'checked' : ''}}>
                        <input type="radio" name="sex" value="2" title="女" {{$data['sex'] == 2 ? 'checked' : ''}}>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">状态：</label>
                    <div class="layui-input-block">
                        <input type="radio" name="status" value="1" title="启用" {{$data['status'] == 1 ? 'checked' : ''}}>
                        <input type="radio" name="status" value="2" title="禁用" {{$data['status'] == 2 ? 'checked' : ''}}>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">后台角色：</label>
                    <div class="layui-input-block">
                        <select name="role" lay-verify="required">
                            <option value=""></option>
                            @foreach($role as $k => $v)
                                <option value="{{$v->id}}" {{$v->id == $data['role_id'] ? 'selected' : ''}}>{{$v->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">员工角色：</label>
                        <div class="layui-input-inline" style="width: 260px;">
                            <input type="radio" name="admin_status" value="1" title="启用" {{$data['admin_status'] == 1 ? 'checked' : ''}}>
                            <input type="radio" name="admin_status" value="2" title="禁用" {{$data['admin_status'] == 2 ? 'checked' : ''}}>
                            <input type="radio" name="admin_status" value="3" title="管理员" {{$data['admin_status'] == 3 ? 'checked' : ''}}>
                        </div>
                        <div class="layui-form-mid">选择门店：</div>
                        <div class="layui-input-inline" style="width: 250px;">
                            <select name="shop" id="shop" lay-filter="shop">
                                <option value=""></option>
                                @foreach($shop as $k => $v)
                                    <option value="{{$v->id}}" {{$v->id == $data['shop_id'] ? 'selected' : ''}}>{{$v->shop_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="layui-form-mid">选择角色：</div>
                        <div class="layui-input-inline" style="width: 250px;">
                            <select name="admin_role" id="admin_role" lay-filter="admin_role">
                                <option value=""></option>
                                @if($data['shop_id'] > 0)
                                    @foreach($admin_role as $k => $v)
                                        <option value="{{$v->id}}" {{$v->id == $data['admin_role_id'] ? 'selected' : ''}}>{{$v->role_name}}</option>
                                    @endforeach
                                    @endif
                            </select>
                        </div>
                    </div>
                </div>
                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label">记事本：</label>
                    <div class="layui-input-block">
                        <textarea name="todolist" class="layui-textarea">{{$data['todolist']}}</textarea>
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
        layui.use('form', function(){
            var form = layui.form;
            form.verify({
                username: function(value, item){ //value：表单的值、item：表单的DOM对象
                    if(/^\d+\d+\d$/.test(value)){
                        return '用户名不能全为数字';
                    }
                }
                ,pass: [
                    /^[\S]{6,20}$/
                    ,'密码必须6到20位，且不能出现空格'
                ]
            });
            //监听提交
            form.on('submit(formDemo)', function(data){
                var index = layer.load(2, {shade: [0.1, '#000000']});
                $.ajax({
                    type: 'put',
                    url: '/admin/'+data.field.id,
                    data: {'_token': '{{csrf_token()}}', 'name': data.field.name, 'phone': data.field.phone, 'photo': data.field.photo, 'sex': data.field.sex, 'status': data.field.status, 'role': data.field.role, 'todolist': data.field.todolist, 'admin_status': data.field.admin_status, 'admin_role': data.field.admin_role},
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
            form.on('select(shop)', function(data){
                $.ajax({
                    type: 'post',
                    url: '/role_list',
                    data: {'_token': '{{csrf_token()}}', 'shop_id': data.value},
                    dataType: 'json',
                    success: function(data){
                        $("#admin_role").html(data.msg);
                        form.render('select');
                    },
                    error: function(){
                        layer.msg('网络异常请重试', {icon: 5, anim: 6});
                    }
                });
            });
        });
    </script>
@endsection