<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\AddressController;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Gift_card_orderController extends Controller
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
                ->whereIn('orders.order_type', [7, 8])
                ->select(['orders.id', 'orders.order_sn', 'orders.order_type', 'users.user_name', 'orders.user_id', 'orders.consignee', 'orders.phone', 'orders.order_amount', 'orders.order_status', 'orders.shipping_status', 'orders.pay_status', 'orders.created_at'])
                ->get();
            $data = [
                'code' => 0,
                'msg' => 'ok',
                'count' => Order::where('order_sn', 'like', '%'.request('order_sn').'%')->whereIn('orders.order_type', [7, 8])->count(),
                'data' => $list
            ];
            return response()->json($data);
        }
        return view('gift_card_order.order');
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
        return view('gift_card_order.order_show', ['data' => $data, 'address' => $address]);
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
}
