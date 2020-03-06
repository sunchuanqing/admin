<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\AddressController;
use App\Models\Order;
use App\Models\Order_good;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
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
                ->where('users.phone', 'like', '%'.request('phone').'%')
                ->where('orders.order_type', 'like', '%'.request('order_type').'%')
                ->where('orders.pay_status', 'like', '%'.request('pay_status').'%')
                ->join('users', 'orders.user_id', '=', 'users.id')
                ->offset($num)
                ->limit($limit)
                ->orderBy('id', 'desc')
                ->select(['orders.id', 'orders.order_sn', 'orders.order_type', 'users.user_name', 'orders.user_id', 'orders.consignee', 'orders.phone', 'orders.order_amount', 'orders.order_status', 'orders.shipping_status', 'orders.pay_status', 'orders.created_at'])
                ->get();
            $data = [
                'code' => 0,
                'msg' => 'ok',
                'count' => Order::where('order_sn', 'like', '%'.request('order_sn').'%')
                    ->where('users.phone', 'like', '%'.request('phone').'%')
                    ->where('orders.order_type', 'like', '%'.request('order_type').'%')
                    ->where('orders.pay_status', 'like', '%'.request('pay_status').'%')
                    ->join('users', 'orders.user_id', '=', 'users.id')
                    ->count(),
                'data' => $list
            ];
            return response()->json($data);
        }
        return view('order.order');
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
//            ->join('order_visits', 'orders.order_sn', '=', 'order_visits.order_sn')
            ->with('order_goods')
            ->with('order_action')
            ->select(['users.user_name', 'orders.id', 'orders.order_sn', 'orders.order_type', 'orders.user_id', 'orders.order_status', 'orders.shipping_status', 'orders.pay_status', 'orders.consignee', 'orders.country', 'orders.province', 'orders.city', 'orders.district', 'orders.street', 'orders.address', 'orders.zipcode', 'orders.phone', 'orders.best_time', 'orders.postscript', 'orders.pay_name', 'orders.goods_amount', 'orders.shipping_fee', 'orders.pay_points', 'orders.pay_points_money', 'orders.coupon', 'orders.server', 'orders.order_amount', 'orders.pay_time', 'orders.shipping_time', 'orders.to_buyer', 'orders.created_at', 'orders.shipping_type', 'orders.gift_card'])
            ->find($id);
//        dd($data->order_action);
        $address = AddressController::Address();
        return view('order.order_show', ['data' => $data, 'address' => $address]);
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
     *
     * 门店开单 -> 陶悠点击取货 -> 工厂点击收货(洗护中) ->
     *
     */
    public function destroy($id)
    {
        //
    }


    public function print_order (Request $request){
        $address = AddressController::Address();
        $admin = Order::find($request->id);
        if(($admin->admin_id == 0) || ($admin->order_type != 1)) return status(400, '不支持打印');
        $order = Order::join('admins', 'orders.admin_id', '=', 'admins.id')->select(['orders.created_at', 'admins.name', 'orders.consignee', 'orders.phone', 'orders.province', 'orders.city', 'orders.district', 'orders.address', 'orders.pay_name', 'orders.order_amount', 'orders.order_sn', 'orders.admin_id'])->find($request->id);
        if(empty($order->province)){
            $add = '自取';
        }else{
            $add = $address['86'][$order->province].$address[$order->province][$order->city].$address[$order->city][$order->district].$order->address;
        }
        $goods = Order_good::where('order_sn', $order->order_sn)->get();
        $str = '';
        $hao = 1;
        foreach ($goods as $k => $v){
            // 附带物件
            $part = json_decode($v['part'], true);
            $part_str = '';
            foreach ($part as $ks => $vs){
                if($vs['name'] == '其他'){
                    $part_str = $part_str.$v['else_part'].'，';
                }else{
                    $part_str = $part_str.$vs['name'].'，';
                }
            }
            // 物件瑕疵
            $flaw = json_decode($v['flaw'], true);
            $flaw_str = '';
            foreach ($flaw as $ks => $vs){
                if($vs['name'] == '其他'){
                    $flaw_str = $flaw_str.$v['else_flaw'].'，';
                }else{
                    $flaw_str = $flaw_str.$vs['name'].'，';
                }
            }
            // 不良效果
            $effect = json_decode($v['effect'], true);
            $effect_str = '';
            foreach ($effect as $ks => $vs){
                if($vs['name'] == '其他'){
                    $effect_str = $effect_str.$v['else_effect'].'，';
                }else{
                    $effect_str = $effect_str.$vs['name'].'，';
                }
            }
            // 服务项目
            $price_list_info = json_decode($v['price_list_info'], true);
            $price_list_info_str = '';
            foreach ($price_list_info as $ks => $vs){
                $price_list_info_str = $price_list_info_str.$vs['name'].'，';
            }
            if($v['shipping_type'] == 1){
                $shipping = '到店自取';
            }else if($v['shipping_type'] == 2){
                $shipping = '快递';
            }else{
                $shipping = '同城上门';
            }
            $str = $str.'<tr><td style="height: 30px; padding: 0 10px; font-size: 12px;">'.$hao.'</td><td style="height: 30px; padding: 0 10px; font-size: 12px;">'.$v->goods_sn.'</td><td style="height: 30px; padding: 0 10px; font-size: 12px;">'.$v->goods_name.'</td><td style="height: 30px; padding: 0 10px; font-size: 12px;">'.$v->brand.'</td><td style="height: 30px; padding: 0 10px; font-size: 12px;">'.$v->colour.'</td><td style="height: 30px; padding: 0 10px; font-size: 12px;">'.rtrim($part_str, "，").'</td><td style="height: 30px; padding: 0 10px; font-size: 12px;">'.$shipping.'</td><td style="height: 30px; padding: 0 10px; font-size: 12px;">'.rtrim($flaw_str, "，").'</td><td style="height: 30px; padding: 0 10px; font-size: 12px;">'.rtrim($effect_str, "，").'</td><td style="height: 30px; padding: 0 10px; font-size: 12px;">'.rtrim($price_list_info_str, "，").'</td><td style="height: 30px; padding: 0 10px; font-size: 12px;">'.$order->pay_name.'</td><td style="height: 30px; padding: 0 10px; font-size: 12px;">'.$v->make_price.'</td><td style="height: 30px; padding: 0 10px; font-size: 12px;">'.$v->to_buyer.'</td></tr>';
            $hao++;
        }
        return status(200, '<div><div style="float: left; width: 50%; height: 60px; font-size: 20px;">MISS LUSSO 护理单</div><div style="float: left; width: 50%; height: 60px;">开单日期：'.$order->created_at.'</div><div style="float: left; width: 25%; height: 40px;">经手人：'.$order->name.'</div><div style="float: left; width: 25%; height: 40px;">客服电话：15850585818</div><div style="float: left; width: 25%; height: 40px;">门店电话：15850585818</div><div style="float: left; width: 100%; height: 60px; font-size: 20px;">顾客信息：</div><div style="float: left; width: 25%; height: 40px;">顾客姓名：'.$order->consignee.'</div><div style="float: left; width: 25%; height: 40px;">联系方式：'.$order->phone.'</div><div style="float: left; width: 50%; height: 40px;">寄回地址：'.$add.'</div><div style="float: left; width: 25%; height: 40px;">付款方式：'.$order->pay_name.'</div><div style="float: left; width: 25%; height: 40px;">原价总计：'.$order->order_amount.'</div><div style="float: left; width: 100%; height: 60px; font-size: 20px;">物件信息：</div><div style="float: left; width: 100%; margin-bottom: 20px;"><table border="1" class="print_table"><tr><th style="height: 30px; padding: 0 10px; font-size: 12px;">序号</th><th style="height: 30px; padding: 0 10px; font-size: 12px;">编号</th><th style="height: 30px; padding: 0 10px; font-size: 12px;">品类</th><th style="height: 30px; padding: 0 10px; font-size: 12px;">品牌</th><th style="height: 30px; padding: 0 10px; font-size: 12px;">颜色</th><th style="height: 30px; padding: 0 10px; font-size: 12px;">附带物件</th><th style="height: 30px; padding: 0 10px; font-size: 12px;">取件方式</th><th style="height: 30px; padding: 0 10px; font-size: 12px;">物件瑕疵</th><th style="height: 30px; padding: 0 10px; font-size: 12px;">不良效果</th><th style="height: 30px; padding: 0 10px; font-size: 12px;">服务项目</th><th style="height: 30px; padding: 0 10px; font-size: 12px;">付款方式</th><th style="height: 30px; padding: 0 10px; font-size: 12px;">原价</th><th style="height: 30px; padding: 0 10px; font-size: 12px;">服务项目备注</th></tr>'.$str.'</table></div><div style="float: left; width: 100%; height: 60px; font-size: 20px;">备注：</div><div style="float: left; width: 100%; height: 30px;">1、请妥善保管本单原件作为日后取件凭证</div><div style="float: left; width: 100%; height: 30px;">2、如有预计工期延迟会提前通知客户</div><div style="float: left; width: 60%; height: 30px;">3、请您在签字前阅读本单全部内容和条款</div><div style="float: left; width: 40%; height: 30px;">顾客签字：</div><div style="float: left; width: 60%; height: 40px;"></div><div style="float: left; width: 40%; height: 40px;">您的签字代表您已经仔细阅读，充分理解并接受该条款</div></div>');
    }
}
