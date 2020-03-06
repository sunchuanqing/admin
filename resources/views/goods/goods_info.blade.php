@extends('index')
@section('content')
    <style>
        .admin-overall{width: 100%; height: 100%;}
        .admin-nav{height: 50px; background-color: #ffffff; line-height: 50px; font-size: 14px; padding-left: 15px;}
        .admin-table{margin: 15px; padding: 0 15px 15px 15px; background-color: #ffffff; border-radius: 5px; height: calc(100% - 95px);}
        .admin-search{width: 100%; height: 60px; border-bottom: 1px solid #f2f2f2 !important;}
        .add{float: right; margin-top: 11px;}
    </style>
    <input type="hidden" name="goods_id" value="{{$goods_id}}">
    <div class="admin-overall">
        <div class="admin-nav">
            <span class="layui-breadcrumb">
                <a href="/">主页</a>
                <a href="/goods">商品列表</a>
                <a><cite>商品图片详情</cite></a>
            </span>
        </div>
        <div class="admin-table">
            <div class="admin-search">
                <a href="/goods_info/create?goods_id={{$goods_id}}" class="layui-btn add">
                    <i class="layui-icon">&#xe608;</i> 添加照片
                </a>
            </div>
            <table id="list" lay-filter="list" lay-data="{id: 'screen'}"></table>
        </div>
    </div>
    <script type="text/html" id="barDemo">
        <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
    </script>
    <script>
        layui.use('table', function(){
            var table = layui.table;
            var tableIns = table.render({
                elem: '#list'
                ,url: '/goods_info?goods_id='+$("input[name='goods_id']").val()
                ,height: 'full-270'
                ,page: {
                    layout: ['prev', 'page', 'next', 'count', 'skip']
                    ,groups: 5
                }
                ,limit: 20
                ,cols: [[
                    {field: 'id', title: 'ID', width:80, sort: true}
                    ,{field: 'goods_thumb', title: '商品图片', width:500, style:'cursor: pointer;', templet: function(d){return '<div class="layer-photos-demo"><img layer-pid="'+d.id+'" layer-src="'+d.original_img+'" src="'+d.goods_thumb+'"></div>';}}
                    ,{field: 'sort', title: '排序（大号在前）', width: 300}
                    ,{field: 'created_at', title: '上传时间', width: 300}
                    ,{fixed: 'right', title:'操作', toolbar: '#barDemo'}
                ]]
                ,done: function(res, curr, count){
                    var rand = parseInt(Math.random() * 7);
                    layer.photos({
                        photos: '.layer-photos-demo'
                        ,anim: rand
                    });
                }
            });
            table.on('tool(list)', function(obj){
                var data = obj.data;
                var layEvent = obj.event;
                var tr = obj.tr;
                if(layEvent === 'del'){
                    layer.confirm('您确定要删除吗?', function(index){
                        $.ajax({
                            type: 'delete',
                            url: '/goods_photo/'+data.id,
                            data: {'_token': '{{csrf_token()}}'},
                            dataType: 'json',
                            success: function(data){
                                layer.close(index);
                                if(data.code === 200){
                                    obj.del();
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
                    });
                }else if(layEvent === 'edit'){
                    window.location.href = "/goods_info/"+data.id+'/edit';
                }
            });
        });
    </script>
@endsection