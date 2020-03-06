<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>江苏嘉然贸易集团有限公司-后台管理</title>
    <link rel="stylesheet" href="{{asset('/layui/css/layui.css')}}">
    <script src="{{asset('/js/data.js')}}"></script>
    <script src="{{asset('/layui/layui.js')}}"></script>
    <script src="{{asset('/js/jquery.min.js')}}"></script>
    <script src="{{asset('/js/echarts.min.js')}}"></script>
    <script src="{{asset('/js/jquery.jqprint-0.3.js')}}"></script>
    <style>
        *{margin: 0; padding: 0;}
        .layui-footer{color: #666; text-align: center;}
        .layui-body{overflow-y: hidden; background-color: #f2f2f2;}
        ::-webkit-scrollbar {width: 8px; height: 8px;}
        ::-webkit-scrollbar-track {background-color: #F2F2F2;}
        ::-webkit-scrollbar-thumb {background-color: #D3D3D3; border-radius: 4px;}
    </style>
</head>
<body class="layui-layout-body">
<div class="layui-layout layui-layout-admin">
    <div class="layui-header">
        <div class="layui-logo">江苏嘉然贸易集团有限公司</div>
        <!-- 头部区域 -->
        <ul class="layui-nav layui-layout-left">
            <li class="layui-nav-item"><a href="/">控制台</a></li>
            <li class="layui-nav-item"><a href="">管理员</a></li>
            <li class="layui-nav-item"><a href="">用户</a></li>
            <li class="layui-nav-item">
                <a href="javascript:;">其它系统</a>
                <dl class="layui-nav-child">
                    <dd><a href="">邮件管理</a></dd>
                    <dd><a href="">消息管理</a></dd>
                    <dd><a href="">授权管理</a></dd>
                </dl>
            </li>
        </ul>
        <ul class="layui-nav layui-layout-right">
            <li class="layui-nav-item">
                <a href="javascript:;">
                    <img src="{{Auth::guard('admin')->user()->photo}}" class="layui-nav-img">
                    {{Auth::guard('admin')->user()->name}}
                </a>
                <dl class="layui-nav-child">
                    <dd><a href="">基本资料</a></dd>
                    <dd><a href="">记事本</a></dd>
                    <dd><a href="">修改密码</a></dd>
                </dl>
            </li>
            <li class="layui-nav-item"><a href="/loginout">退出</a></li>
        </ul>
    </div>
    <div class="layui-side layui-bg-black">
        <div class="layui-side-scroll">
            <!-- 左侧导航区域 -->
            <ul class="layui-nav layui-nav-tree"  lay-filter="test"  lay-shrink="all">
                @role('超级管理员|管理员', 'admin')
                <li class="layui-nav-item {{Request::is('admin*') ? 'layui-nav-itemed' : ''}}">
                    <a href="javascript:;">管理员</a>
                    <dl class="layui-nav-child">
                        <dd class="{{Request::is('admin') ? 'layui-this' : ''}}{{Request::is('admin/*/edit') ? 'layui-this' : ''}}{{Request::is('admin_pass*') ? 'layui-this' : ''}}{{Request::is('admin_account*') ? 'layui-this' : ''}}"><a href="/admin">管理员列表</a></dd>
                        <dd class="{{Request::is('admin/create') ? 'layui-this' : ''}}"><a href="/admin/create">添加管理员</a></dd>
                        <dd class="{{Request::is('admin_log*') ? 'layui-this' : ''}}"><a href="/admin_log">管理员日志</a></dd>
                        <dd class="{{Request::is('admin_recycle*') ? 'layui-this' : ''}}"><a href="/admin_recycle">回收站</a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item {{Request::is('permission*') ? 'layui-nav-itemed' : ''}}{{Request::is('role*') ? 'layui-nav-itemed' : ''}}">
                    <a href="javascript:;">权限管理</a>
                    <dl class="layui-nav-child">
                        <dd class="{{Request::is('permission') ? 'layui-this' : ''}}{{Request::is('permission/*/edit') ? 'layui-this' : ''}}{{Request::is('permission_role/*') ? 'layui-this' : ''}}"><a href="/permission">角色列表</a></dd>
                        <dd class="{{Request::is('permission/create') ? 'layui-this' : ''}}"><a href="/permission/create">添加角色</a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item {{Request::is('oadminrole*') ? 'layui-nav-itemed' : ''}}">
                    <a href="javascript:;">员工端角色</a>
                    <dl class="layui-nav-child">
                        <dd class="{{Request::is('oadminrole') ? 'layui-this' : ''}}{{Request::is('oadminrole/*/edit') ? 'layui-this' : ''}}"><a href="/oadminrole">角色列表</a></dd>
                        <dd class="{{Request::is('oadminrole/create') ? 'layui-this' : ''}}"><a href="/oadminrole/create">添加角色</a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item {{Request::is('coupon*') ? 'layui-nav-itemed' : ''}}">
                    <a href="javascript:;">优惠券</a>
                    <dl class="layui-nav-child">
                        <dd class="{{Request::is('coupon') ? 'layui-this' : ''}}{{Request::is('coupon/*/edit') ? 'layui-this' : ''}}{{Request::is('coupon_*') ? 'layui-this' : ''}}"><a href="/coupon">优惠券列表</a></dd>
                        <dd class="{{Request::is('coupon/create') ? 'layui-this' : ''}}"><a href="/coupon/create">添加优惠券</a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item {{Request::is('banner*') ? 'layui-nav-itemed' : ''}}">
                    <a href="javascript:;">轮播管理</a>
                    <dl class="layui-nav-child">
                        <dd class="{{Request::is('banner') ? 'layui-this' : ''}}{{Request::is('banner/*/edit') ? 'layui-this' : ''}}"><a href="/banner">轮播列表</a></dd>
                        <dd class="{{Request::is('banner/create') ? 'layui-this' : ''}}"><a href="/banner/create">添加轮播</a></dd>
                        <dd class="{{Request::is('banner_type*') ? 'layui-this' : ''}}"><a href="/banner_type">轮播类型</a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item {{Request::is('card*') ? 'layui-nav-itemed' : ''}}">
                    <a href="javascript:;">车护年卡</a>
                    <dl class="layui-nav-child">
                        <dd class="{{Request::is('card') ? 'layui-this' : ''}}{{Request::is('card/*/edit') ? 'layui-this' : ''}}"><a href="/card">卡片列表</a></dd>
                        <dd class="{{Request::is('card/create') ? 'layui-this' : ''}}"><a href="/card/create">添加卡片</a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item {{Request::is('gift_card*') ? 'layui-nav-itemed' : ''}}{{Request::is('entity_gift_card*') ? 'layui-nav-itemed' : ''}}">
                    <a href="javascript:;">礼品卡</a>
                    <dl class="layui-nav-child">
                        <dd class="{{Request::is('gift_card') ? 'layui-this' : ''}}{{Request::is('gift_card/*/edit') ? 'layui-this' : ''}}{{Request::is('entity_gift_card*') ? 'layui-this' : ''}}"><a href="/gift_card">卡片列表</a></dd>
                        <dd class="{{Request::is('gift_card/create') ? 'layui-this' : ''}}"><a href="/gift_card/create">添加卡片</a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item {{Request::is('topic*') ? 'layui-nav-itemed' : ''}}">
                    <a href="javascript:;">专题活动</a>
                    <dl class="layui-nav-child">
                        <dd class="{{Request::is('topic') ? 'layui-this' : ''}}{{Request::is('topic/*/edit') ? 'layui-this' : ''}}"><a href="/topic">专题列表</a></dd>
                        <dd class="{{Request::is('topic/create') ? 'layui-this' : ''}}"><a href="/topic/create">添加专题</a></dd>
                        {{--<dd class="{{Request::is('topic_goods*') ? 'layui-this' : ''}}"><a href="/topic_type">活动</a></dd>--}}
                    </dl>
                </li>
                @endrole
                @role('超级管理员|管理员|奢护店长|车护店长|花艺店长', 'admin')
                <li class="layui-nav-item {{Request::is('user*') ? 'layui-nav-itemed' : ''}}">
                    <a href="javascript:;">会员管理</a>
                    <dl class="layui-nav-child">
                        <dd class="{{Request::is('user') ? 'layui-this' : ''}}{{Request::is('user/*/edit') ? 'layui-this' : ''}}{{Request::is('user_pass*') ? 'layui-this' : ''}}{{Request::is('user_address*') ? 'layui-this' : ''}}{{Request::is('user_account*') ? 'layui-this' : ''}}"><a href="/user">会员列表</a></dd>
                        <dd class="{{Request::is('user/create') ? 'layui-this' : ''}}"><a href="/user/create">添加会员</a></dd>
                        <dd class="{{Request::is('user_recycle*') ? 'layui-this' : ''}}"><a href="/user_recycle">会员回收站</a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item {{Request::is('shop*') ? 'layui-nav-itemed' : ''}}">
                    <a href="javascript:;">门店管理</a>
                    <dl class="layui-nav-child">
                        <dd class="{{Request::is('shop') ? 'layui-this' : ''}}{{Request::is('shop/*/edit') ? 'layui-this' : ''}}{{Request::is('shop_photo*') ? 'layui-this' : ''}}{{Request::is('shop_serve*') ? 'layui-this' : ''}}{{Request::is('shop_price*') ? 'layui-this' : ''}}"><a href="/shop">门店列表</a></dd>
                        <dd class="{{Request::is('shop/create') ? 'layui-this' : ''}}"><a href="/shop/create">添加门店</a></dd>
                        <dd class="{{Request::is('shop_type*') ? 'layui-this' : ''}}"><a href="/shop_type">门店类别</a></dd>
                        <dd class="{{Request::is('shop_car_goods*') ? 'layui-this' : ''}}"><a href="/shop_car_goods">车护商品</a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item {{Request::is('goods*') ? 'layui-nav-itemed' : ''}}">
                    <a href="javascript:;">好货管理</a>
                    <dl class="layui-nav-child">
                        <dd class="{{Request::is('goods') ? 'layui-this' : ''}}{{Request::is('goods/*/edit') ? 'layui-this' : ''}}{{Request::is('goods_attribute*') ? 'layui-this' : ''}}{{Request::is('goods_photo*') ? 'layui-this' : ''}}{{Request::is('goods_info*') ? 'layui-this' : ''}}"><a href="/goods">商品列表</a></dd>
                        <dd class="{{Request::is('goods/create') ? 'layui-this' : ''}}"><a href="/goods/create">添加商品</a></dd>
                        <dd class="{{Request::is('goods_cat*') ? 'layui-this' : ''}}"><a href="/goods_cat">商品分类</a></dd>
                        <dd class="{{Request::is('goods_recycle*') ? 'layui-this' : ''}}"><a href="/goods_recycle">商品回收站</a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item {{Request::is('flower*') ? 'layui-nav-itemed' : ''}}">
                    <a href="javascript:;">花艺管理</a>
                    <dl class="layui-nav-child">
                        <dd class="{{Request::is('flower') ? 'layui-this' : ''}}{{Request::is('flower/*/edit') ? 'layui-this' : ''}}{{Request::is('flower_photo*') ? 'layui-this' : ''}}"><a href="/flower">花束列表</a></dd>
                        <dd class="{{Request::is('flower/create') ? 'layui-this' : ''}}"><a href="/flower/create">添加花束</a></dd>
                        <dd class="{{Request::is('flower_recycle*') ? 'layui-this' : ''}}"><a href="/flower_recycle">花束回收站</a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item {{Request::is('order*') ? 'layui-nav-itemed' : ''}}{{Request::is('good_order*') ? 'layui-nav-itemed' : ''}}{{Request::is('giftcard_order*') ? 'layui-nav-itemed' : ''}}">
                    <a href="javascript:;">订单管理</a>
                    <dl class="layui-nav-child">
                        {{--@role('超级管理员|管理员|奢护管理员', 'admin')--}}

                        {{--@endrole--}}
                        <dd class="{{Request::is('order*') ? 'layui-this' : ''}}"><a href="/order">订单列表</a></dd>
                        <dd class="{{Request::is('good_order*') ? 'layui-this' : ''}}"><a href="/good_order">好货订单</a></dd>
                        <dd class="{{Request::is('giftcard_order*') ? 'layui-this' : ''}}"><a href="/giftcard_order">礼品卡、年卡订单</a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item {{Request::is('after_sale*') ? 'layui-nav-itemed' : ''}}">
                    <a href="javascript:;">售后申请</a>
                    <dl class="layui-nav-child">
                        <dd class="{{Request::is('after_sale*') ? 'layui-this' : ''}}"><a href="/after_sale">申请列表</a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item {{Request::is('comment*') ? 'layui-nav-itemed' : ''}}">
                    <a href="javascript:;">晒一晒</a>
                    <dl class="layui-nav-child">
                        <dd class="{{Request::is('comment') ? 'layui-this' : ''}}"><a href="/comment">晒一晒列表</a></dd>
                    </dl>
                </li>
                @endrole



                @role('超级管理员|', 'admin')
                <li class="layui-nav-item {{Request::is('version*') ? 'layui-nav-itemed' : ''}}">
                    <a href="javascript:;">版本管理</a>
                    <dl class="layui-nav-child">
                        <dd class="{{Request::is('version') ? 'layui-this' : ''}}"><a href="/version">版本列表</a></dd>
                        <dd class="{{Request::is('version/create') ? 'layui-this' : ''}}"><a href="/version/create">添加版本</a></dd>
                    </dl>
                </li>
                @endrole
            </ul>
        </div>
    </div>

    <div class="layui-body">
        <!-- 内容主体区域 -->
        @section('content')

        @show
    </div>

    <div class="layui-footer">
        <!-- 底部固定区域 -->
        版权所有 Copyright © 2019 江苏嘉然贸易集团有限公司
    </div>
</div>
<script>
    layui.use('element', function(){
        var element = layui.element;
    });
</script>
</body>
</html>