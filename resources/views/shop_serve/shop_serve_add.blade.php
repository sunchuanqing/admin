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
                <a href="/shop_serve/{{$shop_id}}">门店项目列表</a>
                <a><cite>添加项目</cite></a>
            </span>
        </div>
        <div class="add">
            <form class="layui-form" action="">
                <input type="hidden" name="shop_id" value="{{$shop_id}}">
                <div class="layui-form-item">
                    <label class="layui-form-label">项目名称：</label>
                    <div class="layui-input-block">
                        <input type="text" name="serve_name" required  lay-verify="required" placeholder="项目名称" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">项目简介：</label>
                    <div class="layui-input-block">
                        <input type="text" name="serve_brief" required  lay-verify="required" placeholder="项目简介" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div id="id_0" class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">项目包含：</label>
                        <div class="layui-input-inline" style="width: 800px;">
                            <select name="price_list" lay-verify="required" lay-search id="price_list_0">
                                <option value=""></option>
                                @foreach($price_list as $k => $v)
                                    <option price="{{$v->price}}" value="{{$v->id}}">{{$v->price_list_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <a class="layui-btn layui-btn-normal" href="javascript:void(0);" onclick="add()"><i class="layui-icon">&#xe608;</i>添加</a>
                    </div>
                </div>
                {{--<div class="layui-form-item">--}}
                    {{--<div class="layui-inline">--}}
                        {{--<label class="layui-form-label"></label>--}}
                        {{--<div class="layui-input-inline" style="width: 573px;">--}}
                            {{--<input type="text" name="serve_item_name" required  lay-verify="required" placeholder="项目子名称 例如：洗车" autocomplete="off" class="layui-input">--}}
                        {{--</div>--}}
                        {{--<div class="layui-form-mid">-</div>--}}
                        {{--<div class="layui-input-inline" style="width: 274px;">--}}
                            {{--<input type="text" name="serve_item_price" required  lay-verify="required" placeholder="市场价" autocomplete="off" class="layui-input">--}}
                        {{--</div>--}}
                        {{--<div class="layui-form-mid">-</div>--}}
                        {{--<div class="layui-input-inline" style="width: 274px;">--}}
                            {{--<input type="text" name="serve_item_num" required  lay-verify="required" placeholder="次数" autocomplete="off" class="layui-input">--}}
                        {{--</div>--}}
                        {{--<div class="layui-form-mid">-</div>--}}
                        {{--<a class="layui-btn layui-btn-danger" href="javascript:void(0);" onclick="del()"><i class="layui-icon">&#xe640;</i>删除</a>--}}
                    {{--</div>--}}
                {{--</div>--}}
                <div class="layui-form-item">
                    <label class="layui-form-label">可用次数：</label>
                    <div class="layui-input-block">
                        <input type="text" name="number" required  lay-verify="required" placeholder="可用次数" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">预计用时：</label>
                    <div class="layui-input-block">
                        <input type="text" name="about_time" required  lay-verify="required" placeholder="预计用时" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div id="add" class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">项目价格：</label>
                        <div class="layui-input-inline" style="width: 274px;">
                            <input type="text" name="shop_price" required  lay-verify="required|number" placeholder="项目价格" autocomplete="off" class="layui-input">
                        </div>
                        {{--<div class="layui-form-mid">-</div>--}}
                        {{--<div class="layui-input-inline" style="width: 274px;">--}}
                            {{--<input type="text" name="promote_price" required  lay-verify="required|number" placeholder="优惠价格" autocomplete="off" class="layui-input">--}}
                        {{--</div>--}}
                        {{--<div class="layui-form-mid">-</div>--}}
                        {{--<div class="layui-input-inline" style="width: 274px;">--}}
                            {{--<input type="text" name="vip_price" required  lay-verify="required|number" placeholder="会员价格" autocomplete="off" class="layui-input">--}}
                        {{--</div>--}}
                    </div>
                </div>
                {{--<div class="layui-form-item">--}}
                    {{--<div class="layui-inline">--}}
                        {{--<label class="layui-form-label">优惠时间：</label>--}}
                        {{--<div class="layui-input-inline" style="width: 274px;">--}}
                            {{--<input type="text" name="promote_start_time" id="promote_start_time" placeholder="开始时间" autocomplete="off" class="layui-input">--}}
                        {{--</div>--}}
                        {{--<div class="layui-form-mid">-</div>--}}
                        {{--<div class="layui-input-inline" style="width: 274px;">--}}
                            {{--<input type="text" name="promote_end_time" id="promote_end_time" placeholder="结束时间" autocomplete="off" class="layui-input">--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
                <div class="layui-form-item">
                    <label class="layui-form-label">项目图片：</label>
                    <div class="layui-input-block">
                        <button type="button" class="layui-btn" id="serveimg">
                            <i class="layui-icon">&#xe67c;</i>上传图片
                        </button>
                        <div class="layui-upload-list">
                            <img id="serveimgs" style="width: 200px; height: 110px;" src="/image/bj.png" alt="">
                            <input type="hidden" name="serve_img" lay-verify="required" value="" id="serve_img">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">有效期：</label>
                    <div class="layui-input-block">
                        <input type="radio" name="valid_type" value="1" title="绝对时效" checked>
                        <input type="radio" name="valid_type" value="2" title="相对时效">
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">绝对时效：</label>
                        <div class="layui-input-inline" style="width: 274px;">
                            <input type="text" name="valid_start_time" id="valid_start_time" lay-verify="required" placeholder="开始时间" autocomplete="off" class="layui-input">
                        </div>
                        <div class="layui-form-mid">-</div>
                        <div class="layui-input-inline" style="width: 274px;">
                            <input type="text" name="valid_end_time" id="valid_end_time" lay-verify="required" placeholder="结束时间" autocomplete="off" class="layui-input">
                        </div>
                        <label class="layui-form-label">相对时效：</label>
                        <div class="layui-input-inline" style="width: 274px;">
                            <input type="text" name="valid_day" required lay-verify="required|number" placeholder="有效天数" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">日期说明：</label>
                    <div class="layui-input-block">
                        <input type="text" name="valid_except" required lay-verify="required" placeholder="例如：周末、法定节假日不可用" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">销售状态：</label>
                        <div class="layui-input-block">
                            <input type="radio" name="is_on_sale" value="1" title="上架" checked>
                            <input type="radio" name="is_on_sale" value="2" title="下架">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">是否热销：</label>
                        <div class="layui-input-block">
                            <input type="radio" name="is_hot" value="1" title="是">
                            <input type="radio" name="is_hot" value="2" title="否" checked>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">是否优惠：</label>
                        <div class="layui-input-block">
                            <input type="radio" name="is_promote" value="1" title="是">
                            <input type="radio" name="is_promote" value="2" title="否" checked>
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">消费积分：</label>
                    <div class="layui-input-block">
                        <input type="text" name="give_integral" required  lay-verify="required" placeholder="购买赠送的消费积分数量" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">等级积分：</label>
                    <div class="layui-input-block">
                        <input type="text" name="rank_integral" required  lay-verify="required" placeholder="购买赠送的等级积分数量" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">虚拟销量：</label>
                    <div class="layui-input-block">
                        <input type="text" name="virtual_sales" required  lay-verify="required" placeholder="虚拟销量" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">适用说明：</label>
                    <div class="layui-input-block">
                        <textarea name="usable_range" placeholder="适用说明" class="layui-textarea"></textarea>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">其他说明：</label>
                    <div class="layui-input-block">
                        <textarea name="else_msg" placeholder="其他说明" class="layui-textarea"></textarea>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">退改说明：</label>
                    <div class="layui-input-block">
                        <textarea name="bc_msg" placeholder="退改说明" class="layui-textarea"></textarea>
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
                elem: '#serveimg'
                ,url: '/qiniu'
                ,accept: 'images'
                ,size: '2048'
                ,data: {'_token': '{{csrf_token()}}'}
                ,before: function(obj){
                    layer.msg('图片上传中', {icon: 16, shade: [0.1, '#000000'], time: 0});
                }
                ,done: function(res){
                    $("#serveimgs").attr('src', res.data.src);
                    $("#serve_img").val(res.data.src);
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
                elem: '#valid_start_time'
                ,type: 'date'
            });
            laydate.render({
                elem: '#valid_end_time'
                ,type: 'date'
            });
        });
        //Demo
        var form1 = null;
        layui.use('form', function(){
            var form = layui.form;
            form1 = form;
            //监听提交
            form.on('submit(formDemo)', function(data){
                var index = layer.load(2, {shade: [0.1, '#000000']});
                var json = [];
                var market_price = 0;
                for (var n = 0; n <= i; n++) {
                    market_price = market_price*data.field.number + $("#price_list_"+n+" option:selected").attr('price')*data.field.number;
                    var j = {};
                    j.serve_item_id = $("#price_list_"+n+" option:selected").val();
                    j.serve_item_name = $("#price_list_"+n+" option:selected").text();
                    j.serve_item_price = $("#price_list_"+n+" option:selected").attr('price');
                    json.push(j);
                }
                var serve_item = JSON.stringify(json);
                $.ajax({
                    type: 'post',
                    url: '/shop_serve',
                    data: {'_token': '{{csrf_token()}}', 'shop_id': data.field.shop_id, 'serve_type_id': data.field.serve_type_id, 'serve_name': data.field.serve_name, 'serve_brief': data.field.serve_brief, 'serve_item': serve_item, 'serve_img': data.field.serve_img, 'valid_type': data.field.valid_type, 'valid_start_time': data.field.valid_start_time, 'valid_end_time': data.field.valid_end_time, 'valid_day': data.field.valid_day, valid_except: data.field.valid_except, number: data.field.number, 'about_time': data.field.about_time, 'market_price': market_price, 'shop_price': data.field.shop_price, 'is_on_sale': data.field.is_on_sale, 'is_hot': data.field.is_hot, 'is_promote': data.field.is_promote, 'give_integral': data.field.give_integral, 'rank_integral': data.field.rank_integral, 'virtual_sales': data.field.virtual_sales, 'usable_range': data.field.usable_range, 'else_msg': data.field.else_msg, 'bc_msg': data.field.bc_msg},
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
        var i = 0;
        function add() {
            i++;
            var price_list = '<select lay-verify="required" id="price_list_'+i+'" lay-search>'+$("select[name='price_list']").html()+'</select>';
            str = '<div id="id_'+i+'" class="layui-form-item">'+
                '<div class="layui-inline">'+
                '<label class="layui-form-label"></label>'+
                '<div class="layui-input-inline" style="width: 800px;">'+
                price_list+
                '</div>'+
                '<a class="layui-btn layui-btn-danger" href="javascript:void(0);" onclick="del('+i+')"><i class="layui-icon">&#xe640;</i>删除</a>'+
                '</div>'+
                '</div>';
            $("#add").before(str);
            form1.render('select');
        }

        function del(id) {
            $("#id_"+id).remove();
        }
    </script>
@endsection