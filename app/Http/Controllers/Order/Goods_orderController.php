<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\AddressController;
use App\Models\Coupon_user;
use App\Models\Order;
use App\Models\Order_action;
use App\Models\Order_actions;
use App\models\User_pay_point;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class Goods_orderController extends Controller
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
            $list = Order::where('order_sn', 'like', '%'.request('order_sn').'%')
                ->join('users', 'orders.user_id', '=', 'users.id')
                ->offset($num)
                ->limit($limit)
                ->orderBy('id', 'desc')
                ->where('orders.order_type', 6)
                ->select(['orders.id', 'orders.order_sn', 'orders.order_type', 'users.user_name', 'orders.user_id', 'orders.consignee', 'orders.phone', 'orders.order_amount', 'orders.order_status', 'orders.shipping_status', 'orders.pay_status', 'orders.created_at'])
                ->get();
            $data = [
                'code' => 0,
                'msg' => 'ok',
                'count' => Order::where('order_sn', 'like', '%'.request('order_sn').'%')->where('orders.order_type', 6)->count(),
                'data' => $list
            ];
            return response()->json($data);
        }
        return view('goods_order.order');
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
        $data = Order::where('order_sn', 'like', '%'.request('order_sn').'%')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->with('order_goods')
            ->with('order_action')
            ->select(['users.user_name', 'orders.id', 'orders.order_sn', 'orders.order_type', 'orders.user_id', 'orders.order_status', 'orders.shipping_status', 'orders.pay_status', 'orders.consignee', 'orders.country', 'orders.province', 'orders.city', 'orders.district', 'orders.street', 'orders.address', 'orders.zipcode', 'orders.phone', 'orders.best_time', 'orders.postscript', 'orders.pay_name', 'orders.goods_amount', 'orders.shipping_fee', 'orders.pay_points', 'orders.pay_points_money', 'orders.coupon', 'orders.server', 'orders.order_amount', 'orders.pay_time', 'orders.shipping_time', 'orders.to_buyer', 'orders.created_at', 'orders.shipping_type', 'orders.gift_card'])
            ->find($id);
        $address = AddressController::Address();
        return view('goods_order.order_show', ['data' => $data, 'address' => $address]);
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
     * Remove the specified resource from storage.
     * 取消订单
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancel_order (Request $request){
        $order = Order::where('order_sn', $request->order_sn)->first();
        if($order->pay_status == 1){
            DB::beginTransaction();
            try {
                // 1.修改订单状态为已取消
                $order->order_status = 5;
                $order->save();
                // 2.返还用户积分 添加积分流水
                if($order->pay_points>0){
                    $user = User::find($order->user_id);
                    $user->pay_points = $user->pay_points+$order->pay_points;
                    $user->save();
                    $user_pay_point = new User_pay_point();
                    $user_pay_point->user_id = $user->id;
                    $user_pay_point->point_change = $order->pay_points;
                    $user_pay_point->point = $user->pay_points;
                    $user_pay_point->change_name = '取消订单返还';
                    $user_pay_point->change_msg = '订单取消返回积分，取消订单号：'.$request->order_sn;
                    $user_pay_point->save();
                }
                // 3.返还用户使用的优惠券
                $coupon_melt = Coupon_user::where('coupon_order', $request->order_sn)->first();
                if(!empty($coupon_melt)){
                    $coupon_melt->status = 1;
                    $coupon_melt->coupon_order = null;
                    $coupon_melt->save();
                }
                // 4.返还商品库存

                // 5.修改虚拟物品状态
                // 6.写入订单操作状态
                $order_action = new Order_actions();
                $order_action->order_sn = $request->order_sn;
                $order_action->action_user = '管理员';
                $order_action->order_status = 5;
                $order_action->shipping_status = 4;
                $order_action->pay_status = 1;
                $order_action->action_note = '管理员取消订单，使用积分、优惠券等已返还用户账户。';
                $order_action->save();
                DB::commit();
                return status(200, '取消成功');
            } catch (QueryException $ex) {
                DB::rollback();
                // 错误信息记录
//            if(!$user_updata) return status(400, '用户信息修改失败');
            }
        }
    }


    /**
     * 订单发货
     *
     */
    public function deliver_goods (Request $request){
        $order = Order::where('order_sn', $request->order_sn)->first();
        if($order->pay_status == 3){
            DB::beginTransaction();
            try {
                // 1.修改物流状态为已取消
                $order->shipping_status = 5;
                $order->shipping_name = $request->shipping_name;
                $order->shipping_sn = $request->shipping_sn;
                $order->to_buyer = $request->to_buyer;
                $order->save();
                // 2.写入订单操作状态
                $order_action = new Order_actions();
                $order_action->order_sn = $request->order_sn;
                $order_action->action_user = '管理员';
                $order_action->order_status = 4;
                $order_action->shipping_status = 5;
                $order_action->pay_status = 3;
                if(empty($request->action_note)){
                    $order_action->action_note = '管理员操作发货（快递公司：'.$request->shipping_name.' 单号：'.$request->shipping_sn.'）';
                }else{
                    $order_action->action_note = $request->action_note;
                }
                $order_action->save();
                DB::commit();
                return status(200, '操作成功');
            } catch (QueryException $ex) {
                DB::rollback();
                return status(200, '操作失败');
                // 错误信息记录
//            if(!$user_updata) return status(400, '用户信息修改失败');
            }
        }
    }


    /**
     * 确认收货
     *
     *
     */
    public function goods_finish (Request $request){
        DB::beginTransaction();
        try {
            if(empty($request->order_id)) return status(40001, '订单id有误');
            // 1.订单变为已完成 已收货
            $order = Order::where('pay_status', 3)->where('order_status', 6)->whereIn('shipping_status', [5, 8])->find($request->order_id);
            if(empty($order)) return status(40002, '订单操作有误');
            $order->order_status = 4;
            $order->shipping_status = 6;
            $order->save();
            // 2.写入订单操作
            $order_action = new Order_action();
            $order_action->order_sn = $order->order_sn;
            $order_action->action_user = '管理员';
            $order_action->order_status = 4;
            $order_action->shipping_status = 6;
            $order_action->pay_status = 3;
            $order_action->action_note = '用户确认收货';
            $order_action->save();
            DB::commit();
            return status(200, '操作成功');
        } catch (QueryException $ex) {
            DB::rollback();
            return status(40003, '确认失败');
        }
    }


    /**
     * 订单退款
     *
     */
    public function refund (Request $request){
        $order = Order::where('order_sn', $request->order_sn)->first();
        if($order->pay_status == 3){
            DB::beginTransaction();
            try {
                // 1.修改订单状态为已取消
                $order->order_status = 5;
                $order->save();
                // 2.返还用户积分 添加积分流水
                if($order->pay_points>0){
                    $user = User::find($order->user_id);
                    $user->pay_points = $user->pay_points+$order->pay_points;
                    $user->save();
                    $user_pay_point = new User_pay_point();
                    $user_pay_point->user_id = $user->id;
                    $user_pay_point->point_change = $order->pay_points;
                    $user_pay_point->point = $user->pay_points;
                    $user_pay_point->change_name = '取消订单返还';
                    $user_pay_point->change_msg = '订单取消返回积分，取消订单号：'.$request->order_sn;
                    $user_pay_point->save();
                }
                // 3.返还用户使用的优惠券
                $coupon_melt = Coupon_user::where('coupon_order', $request->order_sn)->first();
                if(!empty($coupon_melt)){
                    $coupon_melt->status = 1;
                    $coupon_melt->coupon_order = null;
                    $coupon_melt->save();
                }
                // 4.返还商品库存

                // 5.修改虚拟物品状态
                // 6.写入订单操作状态
                $order_action = new Order_actions();
                $order_action->order_sn = $request->order_sn;
                $order_action->action_user = '管理员';
                $order_action->order_status = 5;
                $order_action->shipping_status = 4;
                $order_action->pay_status = 1;
                $order_action->action_note = '管理员取消订单，使用积分、优惠券等已返还用户账户。';
                $order_action->save();
                DB::commit();
                return status(200, '取消成功');
            } catch (QueryException $ex) {
                DB::rollback();
                // 错误信息记录
//            if(!$user_updata) return status(400, '用户信息修改失败');
            }
        }
    }
}
