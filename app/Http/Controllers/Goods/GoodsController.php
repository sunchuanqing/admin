<?php

namespace App\Http\Controllers\Goods;

use App\Models\Category;
use App\Models\Good;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GoodsController extends Controller
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
            $list = Good::with('cat_id')->where('goods_name', 'like', '%'.request('goods_name').'%')->where('goods_sn', 'like', '%'.request('goods_sn').'%')->offset($num)->limit($limit)->get();
            $data = [
                'code' => 0,
                'msg' => 'ok',
                'count' => Good::where('goods_name', 'like', '%'.request('goods_name').'%')->where('goods_sn', 'like', '%'.request('goods_sn').'%')->count(),
                'data' => $list
            ];
            return response()->json($data);
        }
        return view('goods.goods');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cat = Category::where('parent_id', null)->get();
        return view('goods.goods_add', ['cat' => $cat]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $goods = new Good();
        $goods->goods_sn = sn_20();
        $goods->cat_id = $request->cat_id;
        $goods->goods_name = $request->goods_name;
        $goods->goods_weight = $request->goods_weight;
        $goods->market_price = $request->market_price;
        $goods->shop_price = $request->shop_price;
        $goods->vip_price = $request->vip_price;
        $goods->promote_price = $request->promote_price;
        $goods->promote_start_date = $request->promote_start_date;
        $goods->promote_end_date = $request->promote_end_date;
        $goods->goods_brief = $request->goods_brief;
        $goods->goods_thumb = $request->goods_img.'?imageView2/2/w/300/h/300/interlace/0/q/100';
        $goods->goods_img = $request->goods_img.'?imageView2/1/w/500/h/500/q/75|imageslim';
        $goods->original_img = $request->goods_img;
        $goods->goods_video = $request->goods_video;
        $goods->goods_video_img = $request->goods_video_img;
        $goods->is_on_sale = $request->is_on_sale;
        $goods->is_real = $request->is_real;
        $goods->integral = $request->integral;
        $goods->is_best = $request->is_best;
        $goods->is_new = $request->is_new;
        $goods->is_hot = $request->is_hot;
        $goods->is_promote = $request->is_promote;
        if($request->is_real == 2){
            if(empty($request->extension_code)) return status(400, '虚拟代码必填');
        }
        $goods->extension_code = $request->extension_code;
        $goods->give_integral = $request->give_integral;
        $goods->rank_integral = $request->rank_integral;
        $goods->virtual_sales = $request->virtual_sales;
        $goods->texture = $request->texture;
        $goods->scene = $request->scene;
        $goods->brand = $request->brand;
        $goods->place = $request->place;
        $goods->save();
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
        $info = Good::find($id);
        $cat = [];
        $data = Category::find($info -> cat_id);
        $parent = $data->ancestors;
        foreach ($parent as $k => $v){
            $parend_data = Category::where('parent_id', $v->parent_id)->get();
            $cat[$k] = $parend_data;
            $cat[$k]['select'] = $v->id;
        }
        $brother = Category::where('parent_id', $data->parent_id)->get();
        $brother['select'] = $info -> cat_id;
        array_push($cat, $brother);
//        dd($cat);
        return view('goods.goods_update', ['data' => $info, 'cat' => $cat]);
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
        $goods = Good::find($id);
        $goods->cat_id = $request->cat_id;
        $goods->goods_name = $request->goods_name;
        $goods->goods_weight = $request->goods_weight;
        $goods->market_price = $request->market_price;
        $goods->shop_price = $request->shop_price;
        $goods->vip_price = $request->vip_price;
        $goods->promote_price = $request->promote_price;
        $goods->promote_start_date = $request->promote_start_date;
        $goods->promote_end_date = $request->promote_end_date;
        $goods->goods_brief = $request->goods_brief;
        $goods->goods_thumb = $request->goods_img.'?imageView2/2/w/300/h/300/interlace/0/q/100';
        $goods->goods_img = $request->goods_img.'?imageView2/1/w/500/h/500/q/75|imageslim';
        $goods->original_img = $request->goods_img;
        $goods->goods_video = $request->goods_video;
        $goods->goods_video_img = $request->goods_video_img;
        $goods->is_on_sale = $request->is_on_sale;
        $goods->is_real = $request->is_real;
        $goods->integral = $request->integral;
        $goods->is_best = $request->is_best;
        $goods->is_new = $request->is_new;
        $goods->is_hot = $request->is_hot;
        $goods->is_promote = $request->is_promote;
        if($request->is_real == 2){
            if(empty($request->extension_code)) return status(400, '虚拟代码必填');
        }
        $goods->extension_code = $request->extension_code;
        $goods->give_integral = $request->give_integral;
        $goods->rank_integral = $request->rank_integral;
        $goods->virtual_sales = $request->virtual_sales;
        $goods->texture = $request->texture;
        $goods->scene = $request->scene;
        $goods->brand = $request->brand;
        $goods->place = $request->place;
        $goods->save();
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
        $info = Good::find($id);
        $info->delete();
        admin_log('删除了ID为 '.$id.' 的商品');
        return status(200, '删除成功');
    }

    // 商品回收站
    public function goods_recycle (Request $request){
        if($request->ajax()){
            $page = $request->page;
            $limit = $request->limit;
            $num = ($page-1)*$limit;
            $list = Good::with('cat_id')->onlyTrashed()->where('goods_name', 'like', '%'.request('goods_name').'%')->offset($num)->limit($limit)->get();
            $data = [
                'code' => 0,
                'msg' => 'ok',
                'count' => Good::onlyTrashed()->where('goods_name', 'like', '%'.request('goods_name').'%')->count(),
                'data' => $list
            ];
            return response()->json($data);
        }
        return view('goods.goods_recycle');
    }

    // 永久删除管理员
    public function goods_del (Request $request){
        $admin = Good::onlyTrashed()->find($request->id);
        $admin->forceDelete();
        admin_log('永久删除id为 '.$request->id.' 的商品');
        return status('200', '删除成功');
    }

    // 回复删除的商品
    public function goods_recover (Request $request){
        $admin = Good::onlyTrashed()->find($request->id);
        $admin->restore();
        admin_log('恢复了id为 '.$request->id.' 的商品');
        return status('200', '恢复成功');
    }

    // 商品的上下架
    public function goods_is_on_sale (Request $request){
        $goods = Good::find($request->id);
        if($goods->is_on_sale == 1){
            $goods->is_on_sale = 2;
            $msg = '商品已下架';
        }else{
            $goods->is_on_sale = 1;
            $msg = '商品已上架';
        }
        $goods->save();
        admin_log('修改了id为 '.$request->id.' 的商品上下架状态');
        return status(200, $msg, $goods->is_on_sale);
    }

    // 商品单独销售处理
    public function goods_is_alone_sale (Request $request){
        $goods = Good::find($request->id);
        if($goods->is_alone_sale == 1){
            $goods->is_alone_sale = 2;
            $msg = '商品设为赠品';
        }else{
            $goods->is_alone_sale = 1;
            $msg = '商品取消为赠品';
        }
        $goods->save();
        admin_log('修改了id为 '.$request->id.' 的商品单独销售状态');
        return status(200, $msg, $goods->is_alone_sale);
    }

    // 商品精品状态处理
    public function goods_is_best (Request $request){
        $goods = Good::find($request->id);
        if($goods->is_best == 1){
            $goods->is_best = 0;
            $msg = '商品取消为精品';
        }else{
            $goods->is_best = 1;
            $msg = '商品设为精品';
        }
        $goods->save();
        admin_log('修改了id为 '.$request->id.' 的商品精品状态');
        return status(200, $msg, $goods->is_best);
    }

    // 商品新品状态处理
    public function goods_is_new (Request $request){
        $goods = Good::find($request->id);
        if($goods->is_new == 1){
            $goods->is_new = 0;
            $msg = '商品取消为新品';
        }else{
            $goods->is_new = 1;
            $msg = '商品设为为新品';
        }
        $goods->save();
        admin_log('修改了id为 '.$request->id.' 的商品新品状态');
        return status(200, $msg, $goods->is_new);
    }

    // 商品热销状态处理
    public function goods_is_hot (Request $request){
        $goods = Good::find($request->id);
        if($goods->is_hot == 1){
            $goods->is_hot = 0;
            $msg = '商品取消为热销';
        }else{
            $goods->is_hot = 1;
            $msg = '商品设为热销';
        }
        $goods->save();
        admin_log('修改了id为 '.$request->id.' 的商品热销状态');
        return status(200, $msg, $goods->is_hot);
    }

    // 商品促销状态处理
    public function goods_is_promote (Request $request){
        $goods = Good::find($request->id);
        if($goods->is_promote == 1){
            $goods->is_promote = 0;
            $msg = '商品取消促销';
        }else{
            $goods->is_promote = 1;
            $msg = '商品开始促销';
        }
        $goods->save();
        admin_log('修改了id为 '.$request->id.' 的商品促销状态');
        return status(200, $msg, $goods->is_promote);
    }
}
