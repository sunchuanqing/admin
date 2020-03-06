<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>江苏嘉然贸易集团有限公司-后台登录页面</title>
    <link rel="stylesheet" href="{{asset('/layui/css/layui.css')}}">
    <script src="{{asset('/layui/layui.js')}}"></script>
    <script src="{{asset('/js/jquery.min.js')}}"></script>
    <style>
        *{margin:0;padding:0}
        em,i{font-style:normal}
        li{list-style:none}
        body{background: url({{asset('/image/login_bj.jpg')}}) 100% 100%;}
        .admin-login{position: absolute; top: 50%; width: 100%;}
        .admin-login-content{width: 450px; height: 450px; margin: -280px auto 0;}
        .admin-login-logo{width: 300px; height: 100px; margin: 0 auto;}
        .admin-login-logo img{width: 300px; height: 100px; }
        .admin-login-form{width: 450px; height: 340px; margin-top: 30px; border: 10px solid #5AC4F5; background-color: #fff;}
        .admin-login-forms{width: 450px; line-height: 120px; text-align: center; font-size: 22px; color: #0EA1EF;}
        .admin-login-input{width: 320px; margin: 0 auto;}
        .layui-form-pane .layui-form-label{width: 90px; height: 44px; padding: 11px 15px;}
        .layui-form-pane .layui-input-block{margin-left: 90px;}
        .layui-input{height: 44px;}
        .layui-form-mid{padding: 11px 0!important;}
        .layui-form-item .layui-input-inline{width: 140px;}
    </style>
    <script language="JavaScript">
        if (window != top)
            top.location.href = location.href;
    </script>
</head>
<body>
<div class="admin-login">
    <div class="admin-login-content">
        <div class="admin-login-logo">
            {{--<img src="{{asset('/image/adminlogo.png')}}" alt="">--}}
        </div>
        <div class="admin-login-form">
            <div class="admin-login-forms">
                江苏嘉然贸易集团有限公司
            </div>
            <div class="admin-login-input layui-form-pane">
                <form class="layui-form" >
                    <div class="layui-form-item">
                        <label class="layui-form-label">账 号</label>
                        <div class="layui-input-block">
                            <input type="text" name="name" required  lay-verify="required" placeholder="用户名/手机号/邮箱" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">密 码</label>
                        <div class="layui-input-block">
                            <input type="password" name="password" required lay-verify="required" placeholder="登录密码" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn" lay-submit lay-filter="Verify">确认登录</button>
                            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
<script>
    layui.use('form', function(){
        var form = layui.form;
        //监听提交
        form.on('submit(Verify)', function(data){
            var index = layer.load(2, {shade: [0.1, '#000000']});
            $.ajax({
                type: 'post',
                url: '/verify',
                data: {'_token': '{{csrf_token()}}', 'name': data.field.name, 'password': data.field.password},
                dataType: 'json',
                success: function(data){
                    layer.close(index);
                    if(data.code === 200){
                        location.href='/';
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
</html>