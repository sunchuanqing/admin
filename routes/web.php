<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/login', 'Login\LoginController@login');
Route::post('/verify', 'Login\LoginController@verify');
Route::get('/loginout', 'Login\LoginController@loginout');

Route::middleware('adminlogin')->group(function () {
    // 后台首页
    Route::get('/', 'IndexController@index');
    // 管理员
    Route::resource('/admin', 'Admin\AdminController');
    Route::any('/admin_log/{id?}', 'Admin\AdminController@admin_log');// 管理员日志
    Route::any('/admin_recycle', 'Admin\AdminController@admin_recycle');// 管理员回收站
    Route::post('/admin_del/{id}', 'Admin\AdminController@admin_del');// 永久删除管理员
    Route::post('/admin_recover/{id}', 'Admin\AdminController@admin_recover');// 管理员恢复
    Route::any('/admin_pass/{id}', 'Admin\AdminController@admin_pass');// 修改管理员密码
    Route::post('/admin_status/{id}', 'Admin\AdminController@admin_status');// 修改管理员状态
    Route::post('/role_list', 'Admin\AdminController@role_list');// 角色列表
    Route::any('/admin_account/{id}', 'Admin\AdminController@admin_account');// 管理员流水

    // 权限管理
    Route::resource('/permission', 'Permission\PermissionController');
    Route::get('permission_role/{id?}', 'Permission\PermissionController@permission_role');// 分配权限列表
    Route::post('permission_role_do', 'Permission\PermissionController@permission_role_do');// 执行权限分配接口


    // 员工端角色管理
    Route::resource('/oadminrole', 'Adminrole\AdminroleController');

    // 会员管理
    Route::resource('/user', 'User\UserController');
    Route::post('/flag/{id}', 'User\UserController@flag');// 修改会员状态
    Route::any('/user_pass/{id}', 'User\UserController@user_pass');// 修改会员密码
    Route::any('/user_recycle', 'User\UserController@user_recycle');// 会员回收站
    Route::post('/user_del/{id}', 'User\UserController@user_del');// 永久删除会员
    Route::post('/user_recover/{id}', 'User\UserController@user_recover');// 会员恢复
    Route::resource('/user_address', 'User\User_addressController');// 会员地址列表
    Route::any('/user_address_only/{id}', 'User\User_addressController@user_address_only');// 单一会员地址列表
    Route::any('/user_address_add/{id}', 'User\User_addressController@user_address_add');// 会员地址添加
    Route::resource('/user_account', 'User\User_accountController');// 会员账户

    // 门店管理
    Route::resource('/shop', 'Shop\ShopController');
    Route::post('/shop_status/{id}', 'Shop\ShopController@shop_status');// 修改门店营业状态
    Route::resource('/shop_type', 'Shop\Shop_typeController');// 门店类别
    Route::resource('/shop_photo', 'Shop\Shop_photoController');// 门店相册
    Route::resource('/shop_serve', 'Shop\Shop_serveController');// 门店服务项目
    Route::post('/shop_serve_sale/{id}', 'Shop\Shop_serveController@shop_serve_sale');// 修改门店项目销售状态
    Route::post('/shop_serve_hot/{id}', 'Shop\Shop_serveController@shop_serve_hot');// 修改门店项目热销状态
    Route::post('/shop_serve_promote/{id}', 'Shop\Shop_serveController@shop_serve_promote');// 修改门店项目优惠状态
    Route::resource('/shop_serve_photo', 'Shop\Shop_serve_photoController');// 门店服务项目相册
    Route::resource('/shop_price', 'Shop\Shop_priceController');// 门店服务项目类别
    Route::post('/shop_price_child', 'Shop\Shop_priceController@shop_price_child');// 查询分类的子类
    Route::any('/shop_price_list/{id}', 'Shop\Shop_priceController@shop_price_list');// 价目表列表
    Route::any('/shop_price_list_add/{id?}', 'Shop\Shop_priceController@shop_price_list_add');// 价目表添加
    Route::post('/shop_price_list_del/{id?}', 'Shop\Shop_priceController@shop_price_list_del');// 价目表删除
    Route::any('/shop_price_list_update/{id?}', 'Shop\Shop_priceController@shop_price_list_update');// 价目表修改
    Route::resource('/shop_car_goods', 'Shop\Shop_car_goodsController');// 车护商品

    // 优惠券管理
    Route::resource('/coupon', 'Coupon\CouponController');
    Route::post('/coupon_examine', 'Coupon\CouponController@coupon_examine');// 检测优惠券是否需要绑定门店 或者指定商品
    Route::any('/coupon_subject/{id}', 'Coupon\CouponController@coupon_subject');// 检测优惠券是否需要绑定门店 或者指定商品
    Route::post('/coupon_shop', 'Coupon\CouponController@coupon_shop');// 给优惠券绑定主体 门店
    Route::any('/coupon_goods/{id}', 'Coupon\CouponController@coupon_goods');// 给优惠券绑定主体 商品
    Route::post('/coupon_user', 'Coupon\CouponController@coupon_user');// 优惠券后台发放给用户

    // 轮播图管理
    Route::resource('/banner', 'Banner\BannerController');
    Route::post('/banner_status/{id}', 'Banner\BannerController@banner_status');// 轮播图状态修改
    Route::resource('/banner_type', 'Banner\Banner_typeController');// 轮播图类别

    // 好货管理
    Route::resource('/goods', 'Goods\GoodsController');
    Route::any('/goods_recycle', 'Goods\GoodsController@goods_recycle');// 商品回收站
    Route::post('/goods_recover/{id}', 'Goods\GoodsController@goods_recover');// 回复删除的商品
    Route::post('/goods_del/{id}', 'Goods\GoodsController@goods_del');// 永久删除商品
    Route::post('/goods_is_on_sale/{id}', 'Goods\GoodsController@goods_is_on_sale');// 商品上下架
    Route::post('/goods_is_alone_sale/{id}', 'Goods\GoodsController@goods_is_alone_sale');// 商品单独销售处理
    Route::post('/goods_is_best/{id}', 'Goods\GoodsController@goods_is_best');// 商品精品状态处理
    Route::post('/goods_is_new/{id}', 'Goods\GoodsController@goods_is_new');// 商品新品状态处理
    Route::post('/goods_is_hot/{id}', 'Goods\GoodsController@goods_is_hot');// 商品热销状态处理
    Route::post('/goods_is_promote/{id}', 'Goods\GoodsController@goods_is_promote');// 商品促销状态处理
    Route::resource('/goods_cat', 'Goods\Goods_catController');// 商品分类
    Route::post('/cat_child', 'Goods\Goods_catController@cat_child');// 查询分类的子类
    Route::resource('/goods_attribute', 'Goods\Goods_attributeController');// 商品属性 入库
    Route::post('/goods_number/{id}', 'Goods\Goods_attributeController@goods_number');// 商品属性 库存变化
    Route::resource('/goods_photo', 'Goods\Goods_photoController');// 商品相册
    Route::resource('/goods_info', 'Goods\Goods_infoController');// 商品图片详情

    // 订单管理
    Route::resource('/order', 'Order\OrderController');
    Route::resource('/good_order', 'Order\Goods_orderController');
    Route::resource('/giftcard_order', 'Order\Gift_card_orderController');
    Route::post('/cancel_order', 'Order\Goods_orderController@cancel_order');// 取消订单
    Route::post('/deliver_goods', 'Order\Goods_orderController@deliver_goods');// 发货
    Route::post('/goods_finish', 'Order\Goods_orderController@goods_finish');// 确认收货
    Route::post('/refund', 'Order\Goods_orderController@refund');// 退款
    Route::post('/print_order', 'Order\OrderController@print_order');// 打印

    // 活动管理
    Route::resource('/topic', 'Topic\TopicController');
    Route::resource('/topic_type', 'Topic\Topic_typeController');
    Route::resource('/topic_photo', 'Topic\Topic_photoController');

    // 卡片管理
    Route::resource('/card', 'Card\CardController');

    // 礼品卡
    Route::resource('/gift_card', 'Card\Gift_CardController');
    Route::resource('/entity_gift_card', 'Card\Entity_gift_cardController');

    // 花艺管理
    Route::resource('/flower', 'Flower\FlowerController');
    Route::post('/flower_status/{id}', 'Flower\FlowerController@flower_status');// 花艺上下架
    Route::any('/flower_recycle', 'Flower\FlowerController@flower_recycle');// 花艺回收站
    Route::post('/flower_recover/{id}', 'Flower\FlowerController@flower_recover');// 回复删除的花艺
    Route::post('/flower_del/{id}', 'Flower\FlowerController@flower_del');// 永久删除花艺
    Route::resource('/flower_photo', 'Flower\Flower_photoController');// 商品相册


    // 晒一晒管理
    Route::resource('/comment', 'Comment\CommentController');
    Route::post('/comment_hot/{id}', 'Comment\CommentController@comment_hot');// 晒一晒热门处理
    Route::post('/comment_top/{id}', 'Comment\CommentController@comment_top');// 晒一晒置顶处理
    Route::post('/comment_recommend/{id}', 'Comment\CommentController@comment_recommend');// 晒一晒推荐处理
    Route::post('/comment_status/{id}', 'Comment\CommentController@comment_status');// 晒一晒状态处理

    // 售后管理
    Route::resource('/after_sale', 'Order\After_saleController');
    Route::post('/passed', 'Order\After_saleController@passed');// 通过售后审核
    Route::post('/sign_for', 'Order\After_saleController@sign_for');// 物品已寄回
    Route::post('/no_sign_for', 'Order\After_saleController@no_sign_for');// 物品无需寄回
    Route::post('/refund_money', 'Order\After_saleController@refund_money');// 售后单退款
    Route::post('/swap_order', 'Order\After_saleController@swap_order');// 售后单退款
    Route::post('/rework', 'Order\After_saleController@rework');// 售后单奢护返工


    // 版本管理
    Route::resource('/version', 'Version\VersionController');
});
// 文件上传
Route::any('/photos', 'UpdateimageController@photo');
Route::any('/banner_img', 'UpdateimageController@banner_img');
Route::any('/shop_logo', 'UpdateimageController@shop_logo');
Route::any('/qiniu', 'UpdateimageController@qiniu');
