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
                <a href="/shop">门店列表</a>
                <a><cite>添加门店</cite></a>
            </span>
        </div>
        <div class="add">
            <form class="layui-form" action="">
                <div class="layui-form-item">
                    <label class="layui-form-label">门店类别：</label>
                    <div class="layui-input-block">
                        <select name="shop_type_id" lay-verify="required">
                            <option value=""></option>
                            @foreach($shop_type as $k => $v)
                                <option value="{{$v->id}}">{{$v->type_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">门店名称：</label>
                    <div class="layui-input-block">
                        <input type="text" name="shop_name" required  lay-verify="required" placeholder="门店名称" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">门店logo：</label>
                        <div class="layui-input-block">
                            <button type="button" class="layui-btn" id="shoplogo">
                                <i class="layui-icon">&#xe67c;</i>上传logo
                            </button>
                            <div class="layui-upload-list">
                                <img id="shoplogos" style="width: 110px; height: 110px;" src="/image/bj.png" alt="">
                                <input type="hidden" name="shop_logo" lay-verify="required" value="" id="shop_logo">
                            </div>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">短视屏：</label>
                        <div class="layui-input-block">
                            <button type="button" class="layui-btn" id="shopvideo">
                                <i class="layui-icon">&#xe67c;</i>上传视屏
                            </button>
                            <div class="layui-upload-list">
                                <video id="shopvideos" style="width: 200px; height: 110px;" src="" controls="controls"></video>
                                <input type="hidden" name="shop_video" value="" id="shop_video">
                            </div>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">视屏封面：</label>
                        <div class="layui-input-block">
                            <button type="button" class="layui-btn" id="shopvideoimg">
                                <i class="layui-icon">&#xe67c;</i>上传图片
                            </button>
                            <div class="layui-upload-list">
                                <img id="shopvideoimgs" style="width: 200px; height: 110px;" src="/image/bj.png" alt="">
                                <input type="hidden" name="shop_video_img" lay-verify="required" value="" id="shop_video_img">
                            </div>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">门店图片：</label>
                        <div class="layui-input-block">
                            <button type="button" class="layui-btn" id="shopimg">
                                <i class="layui-icon">&#xe67c;</i>上传图片
                            </button>
                            <div class="layui-upload-list">
                                <img id="shopimgs" style="width: 200px; height: 110px;" src="/image/bj.png" alt="">
                                <input type="hidden" name="shop_img" lay-verify="required" value="" id="shop_img">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">营业时间：</label>
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
                    <div class="layui-inline">
                        <label class="layui-form-label">联系门店：</label>
                        <div class="layui-input-inline" style="width: 274px;">
                            <input type="text" name="shop_admin" required  lay-verify="required" placeholder="负责人姓名" autocomplete="off" class="layui-input">
                        </div>
                        <div class="layui-form-mid">-</div>
                        <div class="layui-input-inline" style="width: 274px;">
                            <input type="text" name="shop_phone" required  lay-verify="required" placeholder="门店电话" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">门店地址：</label>
                        <div class="layui-input-inline" style="width: 274px;">
                            <select name="province" id="province" lay-filter="province" lay-verify="required">
                            </select>
                        </div>
                        <div class="layui-form-mid">-</div>
                        <div class="layui-input-inline" style="width: 274px;">
                            <select name="city" id="city" lay-filter="city" lay-verify="required">
                                <option value="">请选择城市</option>
                            </select>
                        </div>
                        <div class="layui-form-mid">-</div>
                        <div class="layui-input-inline" style="width: 274px;">
                            <select name="district" id="district" lay-filter="district" lay-verify="required">
                                <option value="">请选择区/县</option>
                            </select>
                        </div>
                        <div class="layui-form-mid">-</div>
                        <div class="layui-input-inline" style="width: 274px;">
                            <select name="street" id="street" lay-filter="street" lay-verify="required">
                                <option value="">请选择乡/街道</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">详细地址：</label>
                    <div class="layui-input-block">
                        <input type="text" name="address" required  lay-verify="required" placeholder="详细地址" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">营业状态：</label>
                    <div class="layui-input-block">
                        <input type="radio" name="shop_status" value="1" title="营业中" checked>
                        <input type="radio" name="shop_status" value="2" title="休息中">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">门店简介：</label>
                    <div class="layui-input-block">
                        <textarea name="shop_brief" placeholder="请输入内容" class="layui-textarea"></textarea>
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
                elem: '#shoplogo'
                ,url: '/shop_logo'
                ,accept: 'images'
                ,size: '2048'
                ,data: {'_token': '{{csrf_token()}}'}
                ,before: function(obj){
                    layer.msg('图片上传中', {icon: 16, shade: [0.1, '#000000'], time: 0});
                }
                ,done: function(res){
                    $("#shoplogos").attr('src', res.data.src);
                    $("#shop_logo").val(res.data.src);
                    layer.closeAll();
                }
                ,error: function(){
                    layer.closeAll();
                    layer.msg('上传失败请重试', {icon: 5, anim: 6});
                }
            });
            var uploadInst = upload.render({
                elem: '#shopvideo'
                ,url: '/qiniu'
                ,accept: 'video'
                ,size: '5120'
                ,data: {'_token': '{{csrf_token()}}'}
                ,before: function(obj){
                    layer.msg('视屏上传中', {icon: 16, shade: [0.1, '#000000'], time: 0});
                }
                ,done: function(res){
                    $("#shopvideos").attr('src', res.data.src);
                    $("#shop_video").val(res.data.src);
                    layer.closeAll();
                }
                ,error: function(){
                    layer.closeAll();
                    layer.msg('上传失败请重试', {icon: 5, anim: 6});
                }
            });
            var uploadInst = upload.render({
                elem: '#shopvideoimg'
                ,url: '/qiniu'
                ,accept: 'image'
                ,size: '2048'
                ,data: {'_token': '{{csrf_token()}}'}
                ,before: function(obj){
                    layer.msg('视屏上传中', {icon: 16, shade: [0.1, '#000000'], time: 0});
                }
                ,done: function(res){
                    $("#shopvideoimgs").attr('src', res.data.src);
                    $("#shop_video_img").val(res.data.src);
                    layer.closeAll();
                }
                ,error: function(){
                    layer.closeAll();
                    layer.msg('上传失败请重试', {icon: 5, anim: 6});
                }
            });
            var uploadInst = upload.render({
                elem: '#shopimg'
                ,url: '/qiniu'
                ,accept: 'image'
                ,size: '5000'
                ,data: {'_token': '{{csrf_token()}}'}
                ,before: function(obj){
                    layer.msg('图片上传中', {icon: 16, shade: [0.1, '#000000'], time: 0});
                }
                ,done: function(res){
                    $("#shopimgs").attr('src', res.data.src);
                    $("#shop_img").val(res.data.src);
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
                ,type: 'time'
                ,format: 'HH:mm'
            });
            laydate.render({
                elem: '#end_time'
                ,type: 'time'
                ,format: 'HH:mm'
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
                    url: '/shop',
                    data: {'_token': '{{csrf_token()}}', 'shop_type_id': data.field.shop_type_id, 'shop_name': data.field.shop_name, 'shop_logo': data.field.shop_logo, 'shop_video': data.field.shop_video, 'shop_video_img': data.field.shop_video_img, 'shop_img': data.field.shop_img, 'start_time': data.field.start_time, 'end_time': data.field.end_time, 'shop_admin': data.field.shop_admin, 'shop_phone': data.field.shop_phone, 'province': data.field.province, 'city': data.field.city, 'district': data.field.district, 'street': data.field.street, 'address': data.field.address, 'shop_status': data.field.shop_status, 'shop_brief': data.field.shop_brief, citytext: $("#city").find("option:selected").text(), add: $("#district").find("option:selected").text()+$("#street").find("option:selected").text()},
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
            var provinces = '';
            $.each(AREA[86], function (i, v) {
                provinces = provinces+'<option value="'+i+'">'+v+'</option>';
            });
            $("#province").html('<option value="">请选择省份</option>'+provinces);
            form.render('select');

            form.on('select(province)', function(data){
                city = '';
                $.each(AREA[data.value], function (i, v) {
                    city = city+'<option value="'+i+'">'+v+'</option>';
                });
                $("#city").html('<option value="">请选择城市</option>'+city);
                $("#district").html('<option value="">请选择区/县</option>');
                $("#street").html('<option value="">请选择乡/街道</option>');
                form.render('select');
            });

            form.on('select(city)', function(data){
                district = '';
                $.each(AREA[data.value], function (i, v) {
                    district = district+'<option value="'+i+'">'+v+'</option>';
                });
                $("#district").html('<option value="">请选择区/县</option>'+district);
                $("#street").html('<option value="">请选择乡/街道</option>');
                form.render('select');
            });

            form.on('select(district)', function(data){
                street = '';
                $.each(AREA[data.value], function (i, v) {
                    street = street+'<option value="'+i+'">'+v+'</option>';
                });
                $("#street").html('<option value="">请选择乡/街道</option>'+street);
                form.render('select');
            });
        });
    </script>
@endsection