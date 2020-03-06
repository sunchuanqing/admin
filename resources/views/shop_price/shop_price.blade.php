@extends('index')
@section('content')
    <style>
        .admin-overall{width: 100%; height: 100%;}
        .admin-nav{height: 50px; background-color: #ffffff; line-height: 50px; font-size: 14px; padding-left: 15px;}
        .admin-table{margin: 15px; padding: 0 15px 15px 15px; background-color: #ffffff; border-radius: 5px; height: calc(100% - 95px);}
        .admin-search{width: 100%; height: 60px; border-bottom: 1px solid #f2f2f2 !important;}
        .add{float: right; margin-top: 11px;}
        .admin-search form{float: left; margin-top: 11px;}
        .layui-form-item {margin-bottom: 0px;}
        .layui-form-item .layui-inline {margin-bottom: 0px;}
    </style>
    <div class="admin-overall">
        <div class="admin-nav">
            <span class="layui-breadcrumb">
                <a href="/">主页</a>
                <a href="/shop">门店列表</a>
                <a><cite>价目表类别</cite></a>
            </span>
        </div>
        <div class="admin-table">
            <div class="admin-search">
                <a href="/shop_price/create?shop_id={{$shop_id}}" class="layui-btn add">
                    <i class="layui-icon">&#xe608;</i> 添加项目类别
                </a>
                <form class="layui-form" action="">
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <div class="layui-input-inline" style="width: 200px;">
                                <input type="text" name="name" placeholder="价目表类别名称" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <a class="layui-btn layui-btn-warm" id="screen"><i class="layui-icon">&#xe615;</i>查找</a>
                    </div>
                </form>
            </div>
            <input type="hidden" id="shop_id" value="{{$shop_id}}">
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
                ,url: '/shop_price/'+$("#shop_id").val()
                ,height: 'full-270'
                ,page: {
                    layout: ['prev', 'page', 'next', 'count', 'skip']
                    ,groups: 5
                }
                ,limit: 200
                ,cols: [[
                    {field: 'id', title: 'ID', width:100, sort: true}
                    ,{field: 'names', title: '价目表类别名称', width:500, templet: function(d){return d.names+d.name;}}
                    ,{fixed: 'right', title:'操作', toolbar: '#barDemo'}
                ]]
            });
            $("#screen").click(function(){
                tableIns.reload({
                    where: {
                        name: $("input[name='name']").val()
                    }
                    ,page: {
                        curr: 1
                    }
                });
            });
            table.on('tool(list)', function(obj){
                var data = obj.data;
                var layEvent = obj.event;
                var tr = obj.tr;
                if(layEvent === 'del'){
                    layer.confirm('您确定要删除吗?', function(index){
                        $.ajax({
                            type: 'delete',
                            url: '/shop_price/'+data.id,
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
                } else if(layEvent === 'edit'){
                    window.location.href = "/shop_price/"+data.id+'/edit';
                    {{--names = data.namess;--}}
                    {{--layer.prompt({--}}
                        {{--formType: 0,--}}
                        {{--value: data.name,--}}
                        {{--title: '修改分类名称'--}}
                    {{--}, function(value, index, elem){--}}
                        {{--$.ajax({--}}
                            {{--type: 'put',--}}
                            {{--url: '/shop_price/'+data.id,--}}
                            {{--data: {'_token': '{{csrf_token()}}', 'name': value},--}}
                            {{--dataType: 'json',--}}
                            {{--success: function(data){--}}
                                {{--if(data.code === 200){--}}
                                    {{--layer.close(index);--}}
                                    {{--obj.update({--}}
                                        {{--names: names+value,--}}
                                        {{--name: value--}}
                                    {{--});--}}
                                    {{--layer.msg(data.msg, {icon: 1});--}}
                                {{--}else{--}}
                                    {{--layer.close(index);--}}
                                    {{--layer.msg(data.msg, {icon: 5, anim: 6});--}}
                                {{--}--}}
                            {{--},--}}
                            {{--error: function(){--}}
                                {{--layer.close(index);--}}
                                {{--layer.msg('网络异常请重试', {icon: 5, anim: 6});--}}
                            {{--}--}}
                        {{--});--}}
                    {{--});--}}
                }
            });
        });
    </script>
@endsection