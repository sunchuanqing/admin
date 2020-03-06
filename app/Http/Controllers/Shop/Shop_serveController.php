<?php

namespace App\Http\Controllers\Shop;

use App\Models\Price_list;
use App\Models\Shop_serve;
use App\Models\Shop_serve_type;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Shop_serveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $price_list = Price_list::where('shop_id', $request->id)->where('price_list_type_id', '>', 0)->get();
        return view('shop_serve.shop_serve_add', ['shop_id' => $request->id, 'price_list' => $price_list]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $shop_serve = new Shop_serve();
        $shop_serve->shop_id = $request->shop_id;
        $shop_serve->serve_sn = sn_20();
        $shop_serve->serve_name = $request->serve_name;
        $shop_serve->serve_brief = $request->serve_brief;
        $shop_serve->serve_item = $request->serve_item;
        $shop_serve->serve_thumb = $request->serve_img.'?imageView2/2/w/200/h/150/interlace/0/q/100';
        $shop_serve->serve_img = $request->serve_img.'?imageView2/1/w/400/h/300/q/75|imageslim';
        $shop_serve->original_img = $request->serve_img;
        $shop_serve->valid_type = $request->valid_type;
        $shop_serve->valid_start_time = $request->valid_start_time;
        $shop_serve->valid_end_time = $request->valid_end_time;
        $shop_serve->valid_day = $request->valid_day;
        $shop_serve->valid_except = $request->valid_except;
        $shop_serve->number = $request->number;
        $shop_serve->about_time = $request->about_time;
        // 计算市场原价
        $shop_serve->market_price = $request->market_price;
        if($request->shop_price <= $request->market_price){
            $shop_serve->shop_price = $request->shop_price;
        }else{
            return status(400, '项目价格不得大于市场价总和');
        }
        $shop_serve->is_on_sale = $request->is_on_sale;
        $shop_serve->is_hot = $request->is_hot;
        $shop_serve->is_promote = $request->is_promote;
        $shop_serve->give_integral = $request->give_integral;
        $shop_serve->rank_integral = $request->rank_integral;
        $shop_serve->virtual_sales = $request->virtual_sales;
        $shop_serve->usable_range = $request->usable_range;
        $shop_serve->else_msg = $request->else_msg;
        $shop_serve->bc_msg = $request->bc_msg;
        $shop_serve->save();
        return status(200, '添加成功');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if($request->ajax()){
            $page = $request->page;
            $limit = $request->limit;
            $num = ($page-1)*$limit;
            $list = Shop_serve::where('serve_name', 'like', '%'.request('serve_name').'%')->where('shop_id', $id)->offset($num)->limit($limit)->get();
            $data = [
                'code' => 0,
                'msg' => 'ok',
                'count' => Shop_serve::where('serve_name', 'like', '%'.request('serve_name').'%')->where('shop_id', $id)->count(),
                'data' => $list
            ];
            return response()->json($data);
        }
        return view('shop_serve.shop_serve', ['shop_id' => $id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $info = Shop_serve::find($id);
        $data = json_decode($info->serve_item);
        $info['serve_item'] = $data;
        $info['serve_item_count'] = count($data) - 1;
        $price_list = Price_list::where('shop_id', $info->shop_id)->where('price_list_type_id', '>', 0)->get();
        return view('shop_serve.shop_serve_update', ['data' => $info, 'price_list' => $price_list]);
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
        $shop_serve = Shop_serve::find($id);
        $shop_serve->serve_name = $request->serve_name;
        $shop_serve->serve_brief = $request->serve_brief;
        $shop_serve->serve_item = $request->serve_item;
        $shop_serve->serve_thumb = $request->serve_img.'?imageView2/2/w/200/h/150/interlace/0/q/100';
        $shop_serve->serve_img = $request->serve_img.'?imageView2/1/w/400/h/300/q/75|imageslim';
        $shop_serve->original_img = $request->serve_img;
        $shop_serve->valid_type = $request->valid_type;
        $shop_serve->valid_start_time = $request->valid_start_time;
        $shop_serve->valid_end_time = $request->valid_end_time;
        $shop_serve->valid_day = $request->valid_day;
        $shop_serve->valid_except = $request->valid_except;
        $shop_serve->market_price = $request->market_price;
        $shop_serve->number = $request->number;
        $shop_serve->about_time = $request->about_time;
        if($request->shop_price <= $request->market_price){
            $shop_serve->shop_price = $request->shop_price;
        }else{
            return status(400, '项目价格不得大于市场价总和');
        }
        $shop_serve->is_on_sale = $request->is_on_sale;
        $shop_serve->is_hot = $request->is_hot;
        $shop_serve->is_promote = $request->is_promote;
        $shop_serve->give_integral = $request->give_integral;
        $shop_serve->rank_integral = $request->rank_integral;
        $shop_serve->virtual_sales = $request->virtual_sales;
        $shop_serve->usable_range = $request->usable_range;
        $shop_serve->else_msg = $request->else_msg;
        $shop_serve->bc_msg = $request->bc_msg;
        $shop_serve->save();
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
        $flight = Shop_serve::find($id);
        $flight->delete();
        admin_log('删除了ID为 '.$id.' 的门店服务项目');
        return status(200, '删除成功');
    }

    // 修改门店项目销售状态
    public function shop_serve_sale (Request $request){
        $shop_serve = Shop_serve::find($request->id);
        if($shop_serve->is_on_sale == 1){
            $shop_serve->is_on_sale = 2;
            $msg = '下架';
        }else{
            $shop_serve->is_on_sale = 1;
            $msg = '上架';
        }
        $shop_serve->save();
        admin_log('设置项目ID为 '.$request->id.' 的销售状态为 '.$msg);
        return status(200, $msg);
    }

    // 修改门店项目热销状态
    public function shop_serve_hot (Request $request){
        $shop_serve = Shop_serve::find($request->id);
        if($shop_serve->is_hot == 1){
            $shop_serve->is_hot = 2;
        }else{
            $shop_serve->is_hot = 1;
        }
        $shop_serve->save();
        admin_log('设置项目ID为 '.$request->id.' 的热销状态');
        return status(200, '设置成功', $shop_serve->is_hot);
    }

    // 修改门店项目优惠状态
    public function shop_serve_promote (Request $request){
        $shop_serve = Shop_serve::find($request->id);
        if($shop_serve->is_promote == 1){
            $shop_serve->is_promote = 2;
        }else{
            $shop_serve->is_promote = 1;
        }
        $shop_serve->save();
        admin_log('设置项目ID为 '.$request->id.' 的优惠状态');
        return status(200, '设置成功', $shop_serve->is_promote);
    }
}
