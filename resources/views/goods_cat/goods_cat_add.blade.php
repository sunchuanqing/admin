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
                <a href="/goods_cat">商品分类</a>
                <a><cite>添加分类</cite></a>
            </span>
        </div>
        <div class="add">
            <form class="layui-form" action="">
                <div class="layui-form-item">
                    <div class="layui-inline" id="parent_id">
                        <label class="layui-form-label">上级分类：</label>
                        <div class="layui-input-inline" style="width: 200px;">
                            <select lay-filter="filter">
                                <option value="">请选择商品分类</option>
                                @foreach($data as $k=>$v)
                                    <option value="{{$v->id}}">{{$v->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">分类名称：</label>
                    <div class="layui-input-block">
                        <input type="text" name="name" required  lay-verify="required" placeholder="请输入分类名称" autocomplete="off" class="layui-input">
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
        //Demo
        layui.use('form', function(){
            var form = layui.form;
            var parent_id = 0;
            form.on('select(filter)', function(data){
                parent_id = data.value;
                $(this).parent().parent().parent().nextAll().remove();
                $.ajax({
                    type: 'post',
                    url: '/cat_child',
                    data: {'_token': '{{csrf_token()}}', 'parent_id': data.value},
                    dataType: 'json',
                    success: function(data){
                        if(data.code === 200){
                            var option = '';
                            $.each(data.data, function (i, v) {
                                option = option+'<option value="'+v.id+'">'+v.name+'</option>';
                            });
                            $("#parent_id").append('<div class="layui-form-mid">-</div><div class="layui-input-inline" style="width: 200px;"><select name="parent_id" lay-filter="filter"><option value="">请选择商品分类</option>'+option+'</select></div>');
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
                    url: '/goods_cat',
                    data: {'_token': '{{csrf_token()}}', 'parent_id': parent_id, 'name': data.field.name},
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