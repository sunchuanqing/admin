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
                <a href="/shop_price_list/{{$info['shop_id']}}">价目表</a>
                <a><cite>修改价目表</cite></a>
            </span>
        </div>
        <div class="add">
            <form class="layui-form" action="">
                <input type="hidden" name="id" value="{{$info['id']}}">
                <input type="hidden" id="parent_ids" value="{{$info['price_list_type_id']}}">
                <div class="layui-form-item">
                    <div class="layui-inline" id="parent_id">
                        <label class="layui-form-label">门店类别：</label>
                        @foreach($cat as $k=>$v)
                            <div class="layui-input-inline" style="width: 200px;">
                                <select lay-filter="filter"  lay-verify="required">
                                    <option value="">请选择价目分类</option>
                                    @foreach($v as $ks=>$vs)
                                        <option value="{{$vs['id']}}" {{$vs['id'] == $v['select'] ? 'selected' : ''}}>{{$vs['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if($v !== end($cat))
                                <div class="layui-form-mid">-</div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">价目名称：</label>
                    <div class="layui-input-block">
                        <input type="text" name="price_list_name" value="{{$info['price_list_name']}}" required  lay-verify="required" placeholder="价目表名称" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">价格：</label>
                    <div class="layui-input-block">
                        <input type="text" name="price" value="{{$info['price']}}" required  lay-verify="required|number" placeholder="价格" autocomplete="off" class="layui-input">
                    </div>
                </div>
                {{--<div class="layui-form-item">--}}
                {{--<label class="layui-form-label">会员价格：</label>--}}
                {{--<div class="layui-input-block">--}}
                {{--<input type="text" name="vip_price" required  lay-verify="required|number" placeholder="价格" autocomplete="off" class="layui-input">--}}
                {{--</div>--}}
                {{--</div>--}}
                <div class="layui-form-item">
                    <label class="layui-form-label">图片：</label>
                    <div class="layui-input-block">
                        <button type="button" class="layui-btn" id="imgurl">
                            <i class="layui-icon">&#xe67c;</i>上传图片
                        </button>
                        <div class="layui-upload-list">
                            <img id="imgurls" src="{{$info['img']}}" style="height: 150px;" alt="">
                            <input type="hidden" name="img_url" value="{{$info['img']}}" id="img_url">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">推荐级别：</label>
                    <div class="layui-input-block">
                        <input type="text" name="rank" value="{{$info['rank']}}" placeholder="推荐级别（1-5 默认一颗星）" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">建议周期：</label>
                    <div class="layui-input-block">
                        <input type="text" name="period" value="{{$info['period']}}" placeholder="建议周期" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">预计工时：</label>
                    <div class="layui-input-block">
                        <input type="text" name="man_hour" value="{{$info['man_hour']}}" placeholder="预计工时" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">简介：</label>
                    <div class="layui-input-block">
                        <input type="text" name="brief" value="{{$info['brief']}}" placeholder="简介" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">开单提成：</label>
                    <div class="layui-input-block">
                        <input type="text" name="sell_money" value="{{$info['sell_money']}}" required  lay-verify="required|number" placeholder="开单提成" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">施工提成：</label>
                    <div class="layui-input-block">
                        <input type="text" name="job_money" value="{{$info['job_money']}}" required  lay-verify="required|number" placeholder="施工提成" autocomplete="off" class="layui-input">
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
            var parent_id = $("#parent_ids").val();
            form.on('select(filter)', function(data){
                parent_id = data.value;
                $(this).parent().parent().parent().nextAll().remove();
                $.ajax({
                    type: 'post',
                    url: '/shop_price_child',
                    data: {'_token': '{{csrf_token()}}', 'parent_id': data.value},
                    dataType: 'json',
                    success: function(data){
                        if(data.code === 200){
                            var option = '';
                            $.each(data.data, function (i, v) {
                                option = option+'<option value="'+v.id+'">'+v.name+'</option>';
                            });
                            $("#parent_id").append('<div class="layui-form-mid">-</div><div class="layui-input-inline" style="width: 200px;"><select name="parent_id" lay-filter="filter" lay-verify="required"><option value="">请选择价目分类</option>'+option+'</select></div>');
                            form.render('select');
                        }
                    },
                    error: function(){
                        layer.msg('网络异常请重试', {icon: 5, anim: 6});
                    }
                })
            });
            //监听提交
            form.on('submit(formDemo)', function(data){
                var index = layer.load(2, {shade: [0.1, '#000000']});
                $.ajax({
                    type: 'post',
                    url: '/shop_price_list_update/'+data.field.id,
                    data: {'_token': '{{csrf_token()}}', 'price_list_type_id': parent_id, 'shop_id': data.field.shop_id, 'price_list_name': data.field.price_list_name, 'price': data.field.price, 'vip_price': data.field.vip_price, 'img_url': data.field.img_url, 'rank': data.field.rank, 'period': data.field.period, 'man_hour': data.field.man_hour, 'brief': data.field.brief, 'sell_money': data.field.sell_money, 'job_money': data.field.job_money},
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