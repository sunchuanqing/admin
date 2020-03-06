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
                <a href="/flower">花束列表</a>
                <a><cite>修改花束</cite></a>
            </span>
        </div>
        <div class="add">
            <form class="layui-form" action="">
                <input type="hidden" name="id" value="{{$data['id']}}">
                <div class="layui-form-item">
                    <label class="layui-form-label">所属门店：</label>
                    <div class="layui-input-block">
                        <select name="shop_id" lay-verify="required">
                            <option value=""></option>
                            @foreach($shop as $k => $v)
                                <option value="{{$v->id}}" {{$data['shop_id'] == $v->id ? 'selected' : ''}}>{{$v->shop_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">花束名称：</label>
                    <div class="layui-input-block">
                        <input type="text" name="flower_name" value="{{$data['flower_name']}}" required  lay-verify="required" placeholder="花束名称" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">花束图片：</label>
                    <div class="layui-input-block">
                        <button type="button" class="layui-btn" id="flowerimg">
                            <i class="layui-icon">&#xe67c;</i>上传图片
                        </button>
                        <div class="layui-upload-list">
                            <img id="flowerimgs" style="width: 200px; height: 110px;" src="{{$data['flower_img']}}" alt="">
                            <input type="hidden" name="flower_img" lay-verify="required" value="{{$data['flower_img']}}" id="flower_img">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">色调搭配：</label>
                    <div class="layui-input-block">
                        <input type="text" name="color" value="{{$data['color']}}" required  lay-verify="required" placeholder="色调搭配" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">花束内容：</label>
                    <div class="layui-input-block">
                        <input type="text" name="kind" value="{{$data['kind']}}" required  lay-verify="required" placeholder="花束内容" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">花朵数量：</label>
                    <div class="layui-input-block">
                        <input type="text" name="number" value="{{$data['number']}}" required  lay-verify="required" placeholder="花朵数量" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">花束库存：</label>
                    <div class="layui-input-block">
                        <input type="text" name="flower_number" value="{{$data['flower_number']}}" required  lay-verify="required|number" placeholder="花束库存" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">花束价格：</label>
                    <div class="layui-input-block">
                        <input type="text" name="price" value="{{$data['price']}}" required  lay-verify="required|number" placeholder="花束价格" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">花束排序：</label>
                    <div class="layui-input-block">
                        <input type="text" name="sort" value="{{$data['sort']}}" required  lay-verify="required|number" placeholder="花束排序（1-999 大号在前）" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">花束状态：</label>
                    <div class="layui-input-block">
                        <input type="radio" name="status" value="1" title="上架" {{$data['status'] == 1 ? 'checked' : ''}}>
                        <input type="radio" name="status" value="2" title="下架" {{$data['status'] == 2 ? 'checked' : ''}}>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">使用积分：</label>
                    <div class="layui-input-block">
                        <input type="text" name="integral" value="{{$data['integral']}}" required  lay-verify="required|number" placeholder="可以使用的最大积分数量" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div id="add" class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">消费积分：</label>
                        <div class="layui-input-inline" style="width: 274px;">
                            <input type="text" name="give_integral" value="{{$data['give_integral']}}" required  lay-verify="required|number" placeholder="赠送消费积分数量" autocomplete="off" class="layui-input">
                        </div>
                        <div class="layui-form-mid">等级积分：</div>
                        <div class="layui-input-inline" style="width: 274px;">
                            <input type="text" name="rank_integral" value="{{$data['rank_integral']}}" required  lay-verify="required|number" placeholder="赠送等级积分数量" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">虚拟销量：</label>
                    <div class="layui-input-block">
                        <input type="text" name="virtual_sales" value="{{$data['virtual_sales']}}" required  lay-verify="required|number" placeholder="虚拟销量" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">花束简介：</label>
                    <div class="layui-input-block">
                        <textarea name="flower_brief" placeholder="请输入内容" class="layui-textarea">{{$data['flower_brief']}}</textarea>
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
                elem: '#flowerimg'
                ,url: '/qiniu'
                ,accept: 'images'
                ,size: '2048'
                ,data: {'_token': '{{csrf_token()}}'}
                ,before: function(obj){
                    layer.msg('图片上传中', {icon: 16, shade: [0.1, '#000000'], time: 0});
                }
                ,done: function(res){
                    $("#flowerimgs").attr('src', res.data.src);
                    $("#flower_img").val(res.data.src);
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
                    type: 'put',
                    url: '/flower/'+data.field.id,
                    data: {'_token': '{{csrf_token()}}', 'shop_id': data.field.shop_id, 'flower_name': data.field.flower_name, 'flower_img': data.field.flower_img, 'flower_number': data.field.flower_number, 'price': data.field.price, 'flower_brief': data.field.flower_brief, 'status': data.field.status, 'sort': data.field.sort, 'integral': data.field.integral, 'give_integral': data.field.give_integral, 'rank_integral': data.field.rank_integral, 'virtual_sales': data.field.virtual_sales, 'color': data.field.color, 'kind': data.field.kind, 'number': data.field.number},
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