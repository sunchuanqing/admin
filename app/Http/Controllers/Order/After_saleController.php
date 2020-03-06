<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\AddressController;
use App\Models\After_sale;
use App\Models\Coupon_user;
use App\Models\Order;
use App\Models\Order_actions;
use App\Models\Order_good;
use App\Models\Order_visit;
use App\Models\User_account;
use App\Models\User_gift_card_account;
use App\Models\Wx_refund;
use App\User;
use EasyWeChat\Factory;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class After_saleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $page = $request->page;
            $limit = $request->limit;
            $num = ($page-1)*$limit;
            $list = After_sale::where('after_sale_sn', 'like', '%'.request('after_sale_sn').'%')
                ->join('users', 'after_sales.user_id', '=', 'users.id')
                ->join('order_goods', 'after_sales.after_sale_order_goods_id', '=', 'order_goods.id')
                ->offset($num)
                ->limit($limit)
                ->orderBy('id', 'desc')
                ->select(['after_sales.id', 'after_sales.after_sale_sn', 'after_sales.status', 'after_sales.after_sale_order_sn', 'after_sales.after_sale_order_goods_id', 'after_sales.after_sale_order_goods_number', 'after_sales.after_sale_type_name', 'after_sales.after_sale_describe', 'after_sales.after_sale_img', 'users.user_name', 'order_goods.goods_name'])
                ->get();
            $data = [
                'code' => 0,
                'msg' => 'ok',
                'count' => After_sale::where('after_sale_sn', 'like', '%'.request('after_sale_sn').'%')->count(),
                'data' => $list
            ];
            return response()->json($data);
        }
        return view('after_sale.after_sale');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $after_sale = After_sale::find($id);
        $after_sale['img'] = explode(',', $after_sale->after_sale_img);
        $data = Order::where('order_sn', $after_sale->after_sale_order_sn)
            ->join('users', 'orders.user_id', '=', 'users.id')
//            ->join('order_visits', 'orders.order_sn', '=', 'order_visits.order_sn')
            ->with('order_goods')
            ->with('order_action')
            ->select(['users.user_name', 'orders.id', 'orders.order_sn', 'orders.order_type', 'orders.user_id', 'orders.order_status', 'orders.shipping_status', 'orders.pay_status', 'orders.consignee', 'orders.country', 'orders.province', 'orders.city', 'orders.district', 'orders.street', 'orders.address', 'orders.zipcode', 'orders.phone', 'orders.best_time', 'orders.postscript', 'orders.pay_name', 'orders.goods_amount', 'orders.shipping_fee', 'orders.pay_points', 'orders.pay_points_money', 'orders.coupon', 'orders.server', 'orders.order_amount', 'orders.pay_time', 'orders.shipping_time', 'orders.to_buyer', 'orders.created_at', 'orders.shipping_type'])
            ->first();
//        dd($data->order_action);
        $address = AddressController::Address();
        return view('after_sale.after_sale_show', ['data' => $data, 'after_sale' => $after_sale, 'address' => $address]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    /**
     * 通过审核接口
     *
     * 状态：1审核中 2处理中 3处理完毕 4取消服务
     **/
    public function passed (Request $request){
        $after_sale = After_sale::find($request->after_sale_id);
        $after_sale->status = 2;
        $after_sale->after_sale_opinion = $request->after_sale_opinion;
        $after_sale->save();
        $order = Order::where('order_sn', $after_sale->after_sale_order_sn)->first();
        $order_action = new Order_actions();
        $order_action->order_sn = $after_sale->after_sale_order_sn;
        $order_action->action_user = '管理员';
        $order_action->order_status = $order->order_status;
        $order_action->shipping_status = $order->shipping_status;
        $order_action->pay_status = $order->pay_status;
        $order_action->action_note = '管理员同意了用户的售后申请';
        $order_action->save();
        return status(200, '操作成功');
    }

    /**
     * 物品已寄回接口
     */
    public function sign_for (Request $request){
        $after_sale = After_sale::find($request->after_sale_id);
        $after_sale->shipping_status = 3;
        $after_sale->after_sale_opinion = $request->after_sale_opinion;
        $after_sale->save();
        $order = Order::where('order_sn', $after_sale->after_sale_order_sn)->first();
        $order_action = new Order_actions();
        $order_action->order_sn = $after_sale->after_sale_order_sn;
        $order_action->action_user = '管理员';
        $order_action->order_status = $order->order_status;
        $order_action->shipping_status = $order->shipping_status;
        $order_action->pay_status = $order->pay_status;
        $order_action->action_note = '管理员签收用户寄出商品';
        $order_action->save();
        return status(200, '操作成功');
    }

    /**
     * 物品无需寄回接口
     */
    public function no_sign_for (Request $request){
        $after_sale = After_sale::find($request->after_sale_id);
        $after_sale->shipping_status = 3;
        $after_sale->send_back_way = 4;
        $after_sale->after_sale_opinion = $request->after_sale_opinion;
        $after_sale->save();
        $order = Order::where('order_sn', $after_sale->after_sale_order_sn)->first();
        $order_action = new Order_actions();
        $order_action->order_sn = $after_sale->after_sale_order_sn;
        $order_action->action_user = '管理员';
        $order_action->order_status = $order->order_status;
        $order_action->shipping_status = $order->shipping_status;
        $order_action->pay_status = $order->pay_status;
        $order_action->action_note = '管理员签收售后商品（物品为虚拟物品或者还未发出，无需用户寄回）';
        $order_action->save();
        return status(200, '操作成功');
    }

    /**
     * 售后单退款接口
     *
     * 单一商品退换金额以及使用的优惠券 多个商品退款部分金额
     * 可退款订单类型：好货 优惠券 套餐
     * 多个商品使用优惠按照比例计算四舍五入
     * 实际退款金额 = 售后商品价格 - 售后商品价格 / 订单商品总价 * 优惠券抵扣
     * 退款优先级：礼品卡 -> 微信或者储值金
     * 定义优惠券使用情况 1使用了但是无需返还优惠券 2使用了并且返还优惠券  $coupon_type
     */
    public function refund_money (Request $request){
        DB::beginTransaction();
        try {
            $last_order_goods = 1;// 定义是否是订单内最后一笔退款 1不是 2是最后一笔
            $after_sale = After_sale::find($request->after_sale_id);
            $user = User::find($after_sale->user_id);
            $order = Order::where('order_sn', $after_sale->after_sale_order_sn)->first();
            $order_goods = Order_good::find($after_sale->after_sale_order_goods_id);
            // 判断订单物件数量 计算实际退款金额
            $order_goods_number = Order_good::where('order_sn', $after_sale->after_sale_order_sn)->count();
            if($order_goods_number == 1){
                // 单个商品
                $refund_money = $order->order_amount;
                $last_order_goods = 2;
            }else{
                // 多个商品计算部分退款
                // 判断此订单是否为最后一件退款商品
                // 已经退款的商品总数量
                $after_sale_all_number = After_sale::where('after_sale_order_sn', $after_sale->after_sale_order_sn)->where('money_status', 2)->sum('after_sale_order_goods_number');
                // 订单商品总数量
                $order_goods_all_number = Order_good::where('order_sn', $after_sale->after_sale_order_sn)->sum('goods_number');
                if($after_sale_all_number+$after_sale->after_sale_order_goods_number == $order_goods_all_number){
                    // 订单内最后一次售后 实际付款总金额 - 优惠券 - 已经退款的金额  329-50-34=245
                    $after_sale_money = After_sale::where('after_sale_order_sn', $order->order_sn)->where('money_status', 2)->sum('refund_money');
                    $refund_money = $order->goods_amount-$order->coupon-$after_sale_money;
                    // 需要退还已使用优惠券
                    $last_order_goods = 2;
                }else{
                    // 获取订单商品总价 339
                    $goods_amount = $order->goods_amount;
                    // 获取售后商品价格 40
                    $after_sale_money = $order_goods->make_price*$after_sale->after_sale_order_goods_number;
                    // 优惠券抵扣金额 50
                    $coupon = $order->coupon;
                    // 计算应退款金额 34
                    $refund_money = $after_sale_money-round($after_sale_money/$goods_amount*$coupon);
                }
            }
            // 判断是否使用礼品卡 使用礼品卡优先退款礼品卡
            $refund_gift_card_money = After_sale::where('after_sale_order_sn', $order->order_sn)->where('money_status', 2)->sum('refund_gift_card_money');
            // 计算此订单去除全部退款礼品卡后的金额 288-0=288  288-34=254
            $new_gift_card = $order->gift_card-$refund_gift_card_money;
            if($new_gift_card > 0){
                // 判断退款金额是否可以全部退还至礼品卡  1
                if($refund_money-$new_gift_card <= 0){
                    $gift_card = $refund_money;
                    // 判断是否是订单内最后一笔退款
                    if($last_order_goods == 2){
                        if($order->pay_id == 2){
                            $refund_money = 0.01;
                        }else{
                            $refund_money = 0;
                        }
                    }else{
                        $refund_money = 0;
                    }
                    $refund_money_info = '退款成功，资金退回：礼品卡 '.$gift_card.' 元';
                }else{
                    $refund_money = $refund_money-$new_gift_card;
                    $gift_card = $new_gift_card;
                    $refund_money_info = '退款成功，资金退回：礼品卡 '.$gift_card.' 元、';
                }
                // 给用户添加礼品卡金额
                $user->gift_card_money = $user->gift_card_money+$gift_card;
                $user->save();
                // 记录用户储值金账户流水
                $user_gift_card_account = new User_gift_card_account();
                $user_gift_card_account->user_id = $user->id;
                $user_gift_card_account->account_sn = sn_20();
                $user_gift_card_account->money_change = $gift_card;
                $user_gift_card_account->money = $user->gift_card_money;
                $user_gift_card_account->change_name = '订单退款';
                $user_gift_card_account->save();
            }else{
                $gift_card = 0;
                $refund_money_info = '退款成功，资金退回：';
            }
            // 判断优惠券使用情况 必须满足同时满足使用了优惠券和此订单最后一笔退款
            $coupon = 0;
            if(($order->coupon>0) && ($last_order_goods==2)){
                $coupon_user = Coupon_user::where('coupon_order', $order->order_sn)->first();
                $coupon_user->status = 1;
                $coupon_user->coupon_order = null;
                $coupon_user->save();
                $coupon = $order->coupon;
            }
            if($refund_money > 0){
                // 判断支付方式 1储值金 2微信
                if($order->pay_id == 1){
                    // 给用户添加储值金
                    $user->user_money = $user->user_money+$refund_money;
                    $user->save();
                    // 记录用户账户流水
                    $user_account = new User_account();
                    $user_account->account_sn = sn_20();
                    $user_account->user_id = $user->id;
                    $user_account->money_change = $refund_money;
                    $user_account->money = $user->user_money;
                    $user_account->change_name = '退款';
                    $user_account->change_desc = $order_goods->goods_name.' 退款成功';
                    $user_account->save();
                    $refund_money_info = $refund_money_info.'储值金 '.$refund_money.' 元';
                }else if($order->pay_id == 2){
                    $out_refund_no = $after_sale->after_sale_sn;
                    $out_trade_no = $after_sale->after_sale_order_sn;
                    if($order->order_amount == 0){
                        $total_fee = 1;
                    }else{
                        $total_fee = $order->order_amount*100;
                    }
//                    $total_fee = $order->order_amount*100;
                    $refund_fee = $refund_money*100;
                    // 调用微信退款
                    $config = [
                        'app_id'             => 'wxfb223eb0d0373870',
                        'mch_id'             => '1536712341',
                        'key'                => 'jiaranjiaranjiaranjiaranjiaran88',
                        'cert_path'          => 'D:/xampp/htdocs/deya/admin/app/Wecaht/cert/apiclient_cert.pem',
                        'key_path'           => 'D:/xampp/htdocs/deya/admin/app/Wecaht/cert/apiclient_key.pem'
                    ];
                    $app = Factory::payment($config);
                    // 参数分别为：商户订单号、商户退款单号、订单金额、退款金额、其他参数
                    $result = $app->refund->byOutTradeNumber($out_trade_no, $out_refund_no, $total_fee, $refund_fee, [
                        // 可在此处传入其他参数，详细参数见微信支付文档
                        'refund_desc' => $order_goods->goods_name.' 物品退款',
                    ]);
                    if(($result['return_code'] == 'SUCCESS') && ($result['return_msg'] == 'OK')){
                        // 修改售后单状态
                        $refund_money_info = $refund_money_info.'微信 '.$refund_money.' 元；';
                        $wx_refund = new Wx_refund();
                        $wx_refund->out_refund_no = $out_refund_no;
                        $wx_refund->out_trade_no = $out_trade_no;
                        $wx_refund->total_fee = $total_fee;
                        $wx_refund->refund_fee = $refund_fee;
                        $wx_refund->save();
                    }else{
                        return status(40302, '微信支付接口调取失败');
                    }
                }else{
                    return status(40001, '支付方式有误');
                }
            }
            // 修改售后单状态
            $after_sale->status = 3;
            $after_sale->money_status = 2;
            $after_sale->refund_money = $refund_money+$gift_card;
            $after_sale->refund_money_info = $refund_money_info;
            $after_sale->refund_gift_card_money = $gift_card;
            $after_sale->refund_coupon_money = $coupon;
            if($order->pay_id == 2){
                $after_sale->refund_wx_money = $refund_money;
            }else{
                $after_sale->refund_user_money = $refund_money;
            }
            $after_sale->save();
            // 记录订单变更
            $order_action = new Order_actions();
            $order_action->order_sn = $after_sale->after_sale_order_sn;
            $order_action->action_user = '管理员';
            $order_action->order_status = $order->order_status;
            $order_action->shipping_status = $order->shipping_status;
            $order_action->pay_status = $order->pay_status;
            $order_action->action_note = $order_goods->goods_name.' 退款成功，资金已原路返回';
            $order_action->save();
            DB::commit();
            return status(200, '退款成功');
        } catch (QueryException $ex) {
            DB::rollback();
            return status(40004, '退款失败');
        }
    }


    /**
     * 生成换货单
     */
    public function swap_order (Request $request){
        $after_sale = After_sale::find($request->after_sale_id);
        $old_order = Order::where('order_sn', $after_sale->after_sale_order_sn)->first();
        $old_order_goods = Order_good::find($after_sale->after_sale_order_goods_id);
        DB::beginTransaction();
        try {
            // 1.写入订单
            $order_sn = order_sn();
            $order = new Order();
            $order->order_sn = $order_sn;
            $order->order_type = $old_order->order_type;
            $order->user_id = $after_sale->user_id;
            $order->order_status = 6;
            $order->shipping_status = 5;
            $order->pay_status = 3;
            $order->consignee = $after_sale->consignee;
            $order->country = $after_sale->country;
            $order->province = $after_sale->province;
            $order->city = $after_sale->city;
            $order->district = $after_sale->district;
            $order->address = $after_sale->address;
            $order->phone = $after_sale->phone;
            $order->goods_amount = 0;
            $order->shipping_fee = 0;
            $order->pay_points = 0;
            $order->coupon = 0;
            $order->order_amount = 0;
            $order->shipping_type = 2;
            $order->pay_id = $old_order->pay_id;
            $order->pay_name = $old_order->pay_name;
            $order->pay_time = date('Y-m-d H:i:s', time());
            $order->old_order_goods_id = $after_sale->after_sale_order_goods_id;
            $order->shipping_name = $request->shipping_name;
            $order->shipping_sn = $request->shipping_sn;
            $order->to_buyer = $request->to_buyer;
            $order->save();
            // 2.写入订单商品
            $order_goods = new Order_good();
            $order_goods->order_sn = $order_sn;
            $order_goods->goods_sn = $old_order_goods->goods_sn;
            $order_goods->goods_name = $old_order_goods->goods_name;
            $order_goods->goods_img = $old_order_goods->goods_img;
            $order_goods->goods_number = 1;
            $order_goods->make_price = 0;
            $order_goods->attr_name = $old_order_goods->attr_name;
            $order_goods->save();
            // 3.写入订单操作状态
            $order_action = new Order_actions();
            $order_action->order_sn = $order_sn;
            $order_action->action_user = '管理员';
            $order_action->order_status = 6;
            $order_action->shipping_status = 5;
            $order_action->pay_status = 3;
            $order_action->action_note = '售后补发新订单';
            $order_action->save();
            $order_actions = new Order_actions();
            $order_actions->order_sn = $old_order->order_sn;
            $order_actions->action_user = '管理员';
            $order_actions->order_status = $old_order->order_status;
            $order_actions->shipping_status = $old_order->shipping_status;
            $order_actions->pay_status = $old_order->pay_status;
            $order_actions->action_note = '售后补发新订单';
            $order_actions->save();
            // 4.修改售后但状态
            $after_sale->status = 3;
            $after_sale->shipping_status = 4;
            $after_sale->save();
            DB::commit();
            return status(200, '操作成功');
        } catch (QueryException $ex) {
            DB::rollback();
            return status(400, '参数有误');
        }
    }


    /**
     * 生成返工单
     *
     * 物件送回方式：1上门取件 2自行送回到店
     */
    public function rework (Request $request){
        DB::beginTransaction();
        try {
            $order_sn = order_sn();
            $after_sale = After_sale::find($request->after_sale_id);
            $old_order_visit = Order_visit::where('order_sn', $after_sale->after_sale_order_sn)->first();
            $old_order = Order::where('order_sn', $after_sale->after_sale_order_sn)->first();
            $old_order_goods = Order_good::find($after_sale->after_sale_order_goods_id);
            // 判断物件送回方式
            if($after_sale->send_back_way == 1){
                $order_status = 1;
                $shipping_status = 1;
                $pay_status = 1;
                $status = 1;
            }else{
                $order_status = 6;
                $shipping_status = 3;
                $pay_status = 3;
                $status = 2;
                // 写入订单商品
                $order_goods = new Order_good();
                $order_goods->order_sn = $order_sn;
                $order_goods->goods_sn = $old_order_goods->goods_sn;
                $order_goods->goods_name = $old_order_goods->goods_name;
                $order_goods->goods_img = $old_order_goods->goods_img;
                $order_goods->goods_number = 1;
                $order_goods->market_price = $old_order_goods->market_price;;
                $order_goods->make_price = 0;
                $order_goods->attr_name = $old_order_goods->attr_name;
                $order_goods->status = 1;
                $order_goods->shipping_status = 1;
                $order_goods->shipping_type = 3;
                $order_goods->best_time = $old_order_goods->best_time;
                $order_goods->brand = $old_order_goods->brand;
                $order_goods->colour = $old_order_goods->colour;
                $order_goods->part = $old_order_goods->part;
                $order_goods->else_part = $old_order_goods->else_part;
                $order_goods->effect = $old_order_goods->effect;
                $order_goods->else_effect = $old_order_goods->else_effect;
                $order_goods->flaw = $old_order_goods->flaw;
                $order_goods->else_flaw = $old_order_goods->else_flaw;
                $order_goods->price_list_info = $old_order_goods->price_list_info;
                $order_goods->is_rework = 2;
                if(empty($request->after_sale_opinion)) return status(40001, '返工备注必填');
                $order_goods->to_buyer = $request->after_sale_opinion;
                $order_goods->save();
            }
            // 订单信息
            $order = new Order();
            $order->order_sn = $order_sn;
            $order->order_type = 1;
            $order->user_id = $after_sale->user_id;
            $order->order_status = $order_status;
            $order->shipping_status = $shipping_status;
            $order->pay_status = $pay_status;
            $order->shipping_type = 3;
            $order->consignee = $after_sale->consignee;
            $order->phone = $after_sale->phone;
            $order->postscript = $after_sale->postscript;
            $order->shop_id = $old_order->shop_id;
            $order->old_order_goods_id = $after_sale->after_sale_order_goods_id;
            $order->province = $after_sale->province;
            $order->city = $after_sale->city;
            $order->district = $after_sale->district;
            $order->address = $after_sale->address;
            $order->goods_amount = 0;
            $order->shipping_fee = 0;
            $order->pay_points = 0;
            $order->coupon = 0;
            $order->order_amount = 0;
            $order->pay_id = $old_order->pay_id;
            $order->pay_name = $old_order->pay_name;
            $order->pay_time = date('Y-m-d H:i:s', time());
            $order->to_buyer = $request->after_sale_opinion;
            $order->save();
            // 预约信息
            $order_visit = new Order_visit();
            $order_visit->order_sn = $order_sn;
            $order_visit->visit_time = $old_order_visit->visit_time;
            $order_visit->number = 1;
            $order_visit->province = $old_order_visit->province;
            $order_visit->city = $old_order_visit->city;
            $order_visit->district = $old_order_visit->district;
            $order_visit->address = $old_order_visit->address;
            $order_visit->user_name = $old_order_visit->user_name;
            $order_visit->phone = $old_order_visit->phone;
            $order_visit->status = $status;
            // 新订单操作信息
            $order_visit->save();
            $order_action = new Order_actions();
            $order_action->order_sn = $order_sn;
            $order_action->action_user = '管理员';
            $order_action->order_status = $order_status;
            $order_action->shipping_status = $shipping_status;
            $order_action->pay_status = 3;
            $order_action->action_note = '生成售后奢护单';
            $order_action->save();
            // 老订单操作信息
            $order_actions = new Order_actions();
            $order_actions->order_sn = $old_order->order_sn;
            $order_actions->action_user = '管理员';
            $order_actions->order_status = $old_order->order_status;
            $order_actions->shipping_status = $old_order->shipping_status;
            $order_actions->pay_status = $old_order->pay_status;
            $order_actions->action_note = '售后补发新订单';
            $order_actions->save();
            // 修改售后但状态
            $after_sale->status = 3;
            $after_sale->save();
            DB::commit();
            return status(200, '操作成功');
        } catch (QueryException $ex) {
            DB::rollback();
            return status(400, '参数有误');
        }
    }
}
