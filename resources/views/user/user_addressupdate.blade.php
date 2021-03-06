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
                <a href="/user_address_only/{{$data['user_id']}}">地址列表</a>
                <a><cite>修改地址</cite></a>
            </span>
        </div>
        <div class="add">
            <form class="layui-form" action="">
                <input type="hidden" name="id" value="{{$data['id']}}">
                <input type="hidden" id="provinces" value="{{$data['province']}}">
                <input type="hidden" id="citys" value="{{$data['city']}}">
                <input type="hidden" id="districts" value="{{$data['district']}}">
                <input type="hidden" id="streets" value="{{$data['street']}}">
                <div class="layui-form-item">
                    <label class="layui-form-label">收货人：</label>
                    <div class="layui-input-block">
                        <input type="text" name="name" value="{{$data['name']}}" required  lay-verify="required" placeholder="用户名（不能是纯数字）" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">手机号：</label>
                    <div class="layui-input-block">
                        <input type="text" name="phone" value="{{$data['phone']}}" required  lay-verify="required|phone" placeholder="手机号" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">收货地址：</label>
                        <div class="layui-input-inline" style="width: 300px;">
                            <select name="province" id="province" lay-filter="province" lay-verify="required">
                            </select>
                        </div>
                        <div class="layui-input-inline" style="width: 300px;">
                            <select name="city" id="city" lay-filter="city" lay-verify="required">
                                <option value="">请选择城市</option>
                            </select>
                        </div>
                        <div class="layui-input-inline" style="width: 300px;">
                            <select name="district" id="district" lay-filter="district" lay-verify="required">
                                <option value="">请选择区/县</option>
                            </select>
                        </div>
                        <div class="layui-input-inline" style="width: 300px;">
                            <select name="street" id="street" lay-filter="street" lay-verify="required">
                                <option value="">请选择乡/街道</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">详细地址：</label>
                    <div class="layui-input-block">
                        <input type="text" name="address" value="{{$data['address']}}" required lay-verify="required" placeholder="详细地址" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">邮编：</label>
                    <div class="layui-input-block">
                        <input type="text" name="zipcode" value="{{$data['zipcode']}}" required placeholder="邮编" autocomplete="off" class="layui-input">
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
        //Demo
        layui.use('form', function(){
            var form = layui.form;
            //监听提交
            form.on('submit(formDemo)', function(data){
                var index = layer.load(2, {shade: [0.1, '#000000']});
                $.ajax({
                    type: 'put',
                    url: '/user_address/'+data.field.id,
                    data: {'_token': '{{csrf_token()}}', 'name': data.field.name, 'phone': data.field.phone, 'province': data.field.province, 'city': data.field.city, 'district': data.field.district, 'street': data.field.street, 'address': data.field.address, 'zipcode': data.field.zipcode},
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
                var str = '';
                if(i === $("#provinces").val()){
                    str = '<option selected value="'+i+'">'+v+'</option>';
                }else{
                    str = '<option value="'+i+'">'+v+'</option>';
                }
                provinces = provinces+str;
            });
            $("#province").html('<option value="">请选择省份</option>'+provinces);

            var citys = '';
            $.each(AREA[$("#provinces").val()], function (i, v) {
                var str = '';
                if(i === $("#citys").val()){
                    str = '<option selected value="'+i+'">'+v+'</option>';
                }else{
                    str = '<option value="'+i+'">'+v+'</option>';
                }
                citys = citys+str;
            });
            $("#city").html('<option value="">请选择城市</option>'+citys);

            var districts = '';
            $.each(AREA[$("#citys").val()], function (i, v) {
                var str = '';
                if(i === $("#districts").val()){
                    str = '<option selected value="'+i+'">'+v+'</option>';
                }else{
                    str = '<option value="'+i+'">'+v+'</option>';
                }
                districts = districts+str;
            });
            $("#district").html('<option value="">请选择区/县</option>'+districts);

            var streets = '';
            $.each(AREA[$("#districts").val()], function (i, v) {
                var str = '';
                if(i === $("#streets").val()){
                    str = '<option selected value="'+i+'">'+v+'</option>';
                }else{
                    str = '<option value="'+i+'">'+v+'</option>';
                }
                streets = streets+str;
            });
            $("#street").html('<option value="">请选择区/县</option>'+streets);
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