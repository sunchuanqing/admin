<?php

namespace App\Http\Controllers\Shop;

use App\Models\Price_list;
use App\Models\Shop;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Shop_car_goodsController extends Controller
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
            $list = Price_list::where('price_list_name', 'like', '%'.request('price_list_name').'%')
                ->where('price_list_type_id', 0)
                ->join('shops', 'price_lists.shop_id', '=', 'shops.id')
                ->select(['price_lists.id', 'price_lists.price_list_name', 'price_lists.price', 'price_lists.sell_money', 'price_lists.job_money', 'shops.shop_name'])
                ->offset($num)
                ->limit($limit)
                ->get();
            $data = [
                'code' => 0,
                'msg' => 'ok',
                'count' => Price_list::where('price_list_name', 'like', '%'.request('price_list_name').'%')->where('price_list_type_id', 0)->count(),
                'data' => $list
            ];
            return response()->json($data);
        }
        return view('shop_car_goods.shop_car_goods');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $shop = Shop::where('shop_type_id', 1)->get();
        return view('shop_car_goods.shop_car_goods_add', ['shop' => $shop]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $price_list = new Price_list();
        $price_list->price_sn = sn_20();
        $price_list->shop_id = $request->shop_id;
        $price_list->price_list_name = $request->price_list_name;
        $price_list->price = $request->price;
        $price_list->sell_money = $request->sell_money;
        $price_list->job_money = $request->job_money;
        $price_list->save();
        admin_log('添加车护商品 '.$request->price_list_name);
        return status(200, '添加成功');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $info = Price_list::find($id);
        $shop = Shop::where('shop_type_id', 1)->get();
        return view('shop_car_goods.shop_car_goods_update', ['data' => $info, 'shop' => $shop]);
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
        $price_list = Price_list::find($id);
        $price_list->shop_id = $request->shop_id;
        $price_list->price_list_name = $request->price_list_name;
        $price_list->price = $request->price;
        $price_list->sell_money = $request->sell_money;
        $price_list->job_money = $request->job_money;
        $price_list->save();
        admin_log('修改车护商品 '.$request->price_list_name);
        return status(200, '修改成功');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $flight = Price_list::find($id);
        $flight->delete();
        admin_log('删除了ID为 '.$id.' 的车护商品');
        return status(200, '删除成功');
    }
}
