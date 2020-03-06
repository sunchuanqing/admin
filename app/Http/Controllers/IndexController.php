<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\User;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    // 加载主页模板
    public function index (Request $request){
        // 当前月份
        $month = (int)date('m');
        // 营业额计算
        $order_moneys = Order::where('created_at', 'like', date('Y-m', time()).'%')->where('pay_status', 3)->sum('order_amount');
        $order_money = Order::sum('order_amount');
        // 用户总量 当月增加用户量
        $user_sum = User::count();
        $user_month_sum = User::where('created_at', 'like', date('Y-m', time()).'%')->count();
        // 总订单量 当月订单量
        $order_sum = Order::count();
        $order_month_sum = Order::where('created_at', 'like', date('Y-m', time()).'%')->count();
        // 购买年卡的金额
        $card_money = Order::where('order_type', 7)->where('pay_status', 3)->sum('order_amount');
        $card_month_money = Order::where('created_at', 'like', date('Y-m', time()).'%')->where('pay_status', 3)->where('order_type', 7)->sum('order_amount');
        // 花艺订单每月消费统计
        $flower = [];
        $car = [];
        $luxury = [];
        $rests = [];
        for ($i = 1; $i<=$month ;$i++){
            if($i < 10){
                $time = date('Y-0', time()).$i;
            }else{
                $time = date('Y-', time()).$i;
            }
            $flower_order_money = Order::where('order_type', 3)->where('created_at', 'like', $time.'%')->sum('order_amount');
            array_push($flower, (int)$flower_order_money);
            $car_order_money = Order::where('order_type', 2)->where('created_at', 'like', $time.'%')->sum('order_amount');
            array_push($car, (int)$car_order_money);
            $luxury_order_money = Order::where('order_type', 1)->where('created_at', 'like', $time.'%')->sum('order_amount');
            array_push($luxury, (int)$luxury_order_money);
            $rests_order_money = Order::whereIn('order_type', [4, 5, 6, 7])->where('created_at', 'like', $time.'%')->sum('order_amount');
            array_push($rests, (int)$rests_order_money);
        }
        $data = [
            'month' => $month,
            'order_amounts' => $order_moneys,
            'order_amount' => $order_money,
            'user_sum' => $user_sum,
            'user_month_sum' => $user_month_sum,
            'order_sum' => $order_sum,
            'order_month_sum' => $order_month_sum,
            'card_money' => $card_money,
            'card_month_money' => $card_month_money,
            'flower_order_money' => json_encode($flower),
            'car_order_money' => json_encode($car),
            'luxury_order_money' => json_encode($luxury),
            'rests_order_money' => json_encode($rests),
        ];
//        return $data['order_amount'];
        return view('index.index', ['data' => $data]);
    }
}
