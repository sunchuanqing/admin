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
                <a href="/goods">商品列表</a>
                <a><cite>修改商品</cite></a>
            </span>
        </div>
        <div class="add">
            <form class="layui-form" action="">
                <input type="hidden" name="id" value="{{$data['id']}}">
                <input type="hidden" id="cat_id" value="{{$data['cat_id']}}">
                <div class="layui-form-item">
                    <div class="layui-inline" id="parent_id">
                        <label class="layui-form-label">商品分类：</label>
                        @foreach($cat as $k=>$v)
                            <div class="layui-input-inline" style="width: 200px;">
                                <select lay-filter="filter"  lay-verify="required">
                                    <option value="">请选择商品分类</option>
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
                    <label class="layui-form-label">商品名称：</label>
                    <div class="layui-input-block">
                        <input type="text" name="goods_name" value="{{$data['goods_name']}}" required  lay-verify="required" placeholder="商品名称" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">商品重量：</label>
                    <div class="layui-input-block">
                        <input type="text" name="goods_weight" value="{{$data['goods_weight']}}" placeholder="商品重量（单位 g）" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">门店图片：</label>
                        <div class="layui-input-block">
                            <button type="button" class="layui-btn" id="goodsimg">
                                <i class="layui-icon">&#xe67c;</i>上传图片
                            </button>
                            <div class="layui-upload-list">
                                <img id="goodsimgs" style="width: 200px; height: 110px;" src="{{$data['goods_img']}}" alt="">
                                <input type="hidden" name="goods_img" lay-verify="required" value="{{$data['original_img']}}" id="goods_img">
                            </div>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">短视屏：</label>
                        <div class="layui-input-block">
                            <button type="button" class="layui-btn" id="goodsvideo">
                                <i class="layui-icon">&#xe67c;</i>上传视屏
                            </button>
                            <div class="layui-upload-list">
                                <video id="goodsvideos" style="width: 200px; height: 110px;" src="{{$data['goods_video']}}" controls="controls"></video>
                                <input type="hidden" name="goods_video" value="{{$data['goods_video']}}" id="goods_video">
                            </div>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">视屏封面：</label>
                        <div class="layui-input-block">
                            <button type="button" class="layui-btn" id="goodsvideoimg">
                                <i class="layui-icon">&#xe67c;</i>上传图片
                            </button>
                            <div class="layui-upload-list">
                                <img id="goodsvideoimgs" style="width: 200px; height: 110px;" src="{{$data['goods_video_img']}}" alt="">
                                <input type="hidden" name="goods_video_img" value="{{$data['goods_video_img']}}" id="goods_video_img">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">市场价格：</label>
                        <div class="layui-input-inline" style="width: 274px;">
                            <input type="text" name="market_price" value="{{$data['market_price']}}" required  lay-verify="required|number" placeholder="市场价格" autocomplete="off" class="layui-input">
                        </div>
                        <div class="layui-form-label">本店价格：</div>
                        <div class="layui-input-inline" style="width: 274px;">
                            <input type="text" name="shop_price" value="{{$data['shop_price']}}" required  lay-verify="required|number" placeholder="本店价格" autocomplete="off" class="layui-input">
                        </div>
                        <div class="layui-form-label">会员价格：</div>
                        <div class="layui-input-inline" style="width: 274px;">
                            <input type="text" name="vip_price" value="{{$data['vip_price']}}" placeholder="会员价格" autocomplete="off" class="layui-input">
                        </div>
                        <div class="layui-form-label">促销价格：</div>
                        <div class="layui-input-inline" style="width: 274px;">
                            <input type="text" name="promote_price" value="{{$data['promote_price']}}" placeholder="促销价格" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">促销时间：</label>
                        <div class="layui-input-inline" style="width: 274px;">
                            <input type="text" name="promote_start_date" value="{{$data['promote_start_date']}}" id="promote_start_date" placeholder="开始时间" autocomplete="off" class="layui-input">
                        </div>
                        <div class="layui-form-mid">-</div>
                        <div class="layui-input-inline" style="width: 274px;">
                            <input type="text" name="promote_end_date" value="{{$data['promote_end_date']}}" id="promote_end_date" placeholder="结束时间" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">销售状态：</label>
                        <div class="layui-input-block">
                            <input type="radio" name="is_on_sale" value="1" title="上架" {{$data['is_on_sale'] == 1 ? 'checked' : ''}}>
                            <input type="radio" name="is_on_sale" value="2" title="下架" {{$data['is_on_sale'] == 2 ? 'checked' : ''}}>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">是否实体：</label>
                        <div class="layui-input-block">
                            <input type="radio" name="is_real" value="1" title="是"  {{$data['is_real'] == 1 ? 'checked' : ''}}>
                            <input type="radio" name="is_real" value="2" title="否" {{$data['is_real'] == 2 ? 'checked' : ''}}>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">是否精品：</label>
                        <div class="layui-input-block">
                            <input type="radio" name="is_best" value="1" title="是" {{$data['is_best'] == 1 ? 'checked' : ''}}>
                            <input type="radio" name="is_best" value="2" title="否" {{$data['is_best'] == 2 ? 'checked' : ''}}>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">是否新品：</label>
                        <div class="layui-input-block">
                            <input type="radio" name="is_new" value="1" title="是" {{$data['is_new'] == 1 ? 'checked' : ''}}>
                            <input type="radio" name="is_new" value="2" title="否" {{$data['is_new'] == 2 ? 'checked' : ''}}>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">是否热销：</label>
                        <div class="layui-input-block">
                            <input type="radio" name="is_hot" value="1" title="是" {{$data['is_hot'] == 1 ? 'checked' : ''}}>
                            <input type="radio" name="is_hot" value="2" title="否" {{$data['is_hot'] == 2 ? 'checked' : ''}}>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">是否促销：</label>
                        <div class="layui-input-block">
                            <input type="radio" name="is_promote" value="1" title="是" {{$data['is_promote'] == 1 ? 'checked' : ''}}>
                            <input type="radio" name="is_promote" value="2" title="否" {{$data['is_promote'] == 2 ? 'checked' : ''}}>
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">虚拟代码：</label>
                    <div class="layui-input-block">
                        <input type="text" name="extension_code" value="{{$data['extension_code']}}" required placeholder='虚拟商品代码 代码格式：{"type":8,"id":1}' autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">使用积分：</label>
                    <div class="layui-input-block">
                        <input type="text" name="integral" value="{{$data['integral']}}" required  lay-verify="required|number" placeholder="购买该商品可用的最大积分数量" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div id="add" class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">消费积分：</label>
                        <div class="layui-input-inline" style="width: 274px;">
                            <input type="text" name="give_integral" value="{{$data['give_integral']}}" placeholder="赠送消费积分数量" autocomplete="off" class="layui-input">
                        </div>
                        <div class="layui-form-label">等级积分：</div>
                        <div class="layui-input-inline" style="width: 274px;">
                            <input type="text" name="rank_integral" value="{{$data['rank_integral']}}" placeholder="赠送等级积分数量" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">虚拟销量：</label>
                    <div class="layui-input-block">
                        <input type="text" name="virtual_sales" value="{{$data['virtual_sales']}}" placeholder="虚拟销量" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">材质：</label>
                    <div class="layui-input-block">
                        <input type="text" name="texture" value="{{$data['texture']}}" lay-verify="required" placeholder="材质" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">使用场景：</label>
                    <div class="layui-input-block">
                        <input type="text" name="scene" value="{{$data['scene']}}" lay-verify="required" placeholder="使用场景" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">品牌：</label>
                    <div class="layui-input-block">
                        <input type="text" name="brand" value="{{$data['brand']}}" lay-verify="required" placeholder="品牌" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">产地：</label>
                    <div class="layui-input-block">
                        <input type="text" name="place" value="{{$data['place']}}" lay-verify="required" placeholder="产地" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">商品简介：</label>
                    <div class="layui-input-block">
                        <input type="text" name="goods_brief" value="{{$data['goods_brief']}}" placeholder="商品简介" autocomplete="off" class="layui-input">
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
                elem: '#goodsimg'
                ,url: '/qiniu'
                ,accept: 'images'
                ,size: '2048'
                ,data: {'_token': '{{csrf_token()}}'}
                ,before: function(obj){
                    layer.msg('图片上传中', {icon: 16, shade: [0.1, '#000000'], time: 0});
                }
                ,done: function(res){
                    $("#goodsimgs").attr('src', res.data.src);
                    $("#goods_img").val(res.data.src);
                    layer.closeAll();
                }
                ,error: function(){
                    layer.closeAll();
                    layer.msg('上传失败请重试', {icon: 5, anim: 6});
                }
            });
            var uploadInst = upload.render({
                elem: '#goodsvideo'
                ,url: '/qiniu'
                ,accept: 'video'
                ,size: '5120'
                ,data: {'_token': '{{csrf_token()}}'}
                ,before: function(obj){
                    layer.msg('视屏上传中', {icon: 16, shade: [0.1, '#000000'], time: 0});
                }
                ,done: function(res){
                    $("#goodsvideos").attr('src', res.data.src);
                    $("#goods_video").val(res.data.src);
                    layer.closeAll();
                }
                ,error: function(){
                    layer.closeAll();
                    layer.msg('上传失败请重试', {icon: 5, anim: 6});
                }
            });
            //执行实例
            var uploadInst = upload.render({
                elem: '#goodsvideoimg'
                ,url: '/qiniu'
                ,accept: 'images'
                ,size: '2048'
                ,data: {'_token': '{{csrf_token()}}'}
                ,before: function(obj){
                    layer.msg('图片上传中', {icon: 16, shade: [0.1, '#000000'], time: 0});
                }
                ,done: function(res){
                    $("#goodsvideoimgs").attr('src', res.data.src);
                    $("#goods_video_img").val(res.data.src);
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
                elem: '#promote_start_date'
                ,type: 'datetime'
            });
            laydate.render({
                elem: '#promote_end_date'
                ,type: 'datetime'
            });
        });
        //Demo
        layui.use('form', function(){
            var form = layui.form;
            var cat_id = $("#cat_id").val();
            form.on('select(filter)', function(data){
                cat_id = data.value;
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
                    type: 'put',
                    url: '/goods/'+data.field.id,
                    data: {'_token': '{{csrf_token()}}', 'cat_id': cat_id, 'goods_name': data.field.goods_name, 'goods_weight': data.field.goods_weight, 'market_price': data.field.market_price, 'shop_price': data.field.shop_price, 'vip_price': data.field.vip_price, 'promote_price': data.field.promote_price, 'promote_start_date': data.field.promote_start_date, 'promote_end_date': data.field.promote_end_date, 'goods_brief': data.field.goods_brief, 'goods_img': data.field.goods_img, 'goods_video': data.field.goods_video, 'goods_video_img': data.field.goods_video_img, 'is_on_sale': data.field.is_on_sale, 'is_real': data.field.is_real, 'integral': data.field.integral, 'is_best': data.field.is_best, 'is_new': data.field.is_new, 'is_hot': data.field.is_hot, 'is_promote': data.field.is_promote, 'extension_code': data.field.extension_code, 'give_integral': data.field.give_integral, 'rank_integral': data.field.rank_integral, 'virtual_sales': data.field.virtual_sales, 'texture': data.field.texture, 'scene': data.field.scene, 'brand': data.field.brand, 'place': data.field.place},
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