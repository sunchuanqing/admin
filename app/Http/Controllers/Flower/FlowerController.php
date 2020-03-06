<?php

namespace App\Http\Controllers\Flower;

use App\Models\Flower;
use App\Models\Shop;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FlowerController extends Controller
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
            $list = Flower::where('flower_name', 'like', '%'.request('flower_name').'%')
                ->where('flower_sn', 'like', '%'.request('flower_sn').'%')
                ->offset($num)
                ->limit($limit)
                ->get();
            $data = [
                'code' => 0,
                'msg' => 'ok',
                'count' => Flower::where('flower_name', 'like', '%'.request('flower_name').'%')->where('flower_sn', 'like', '%'.request('flower_sn').'%')->count(),
                'data' => $list
            ];
            return response()->json($data);
        }
        return view('flower.flower');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $shop = Shop::where('shop_type_id', 3)->get();
        return view('flower.flower_add', ['shop' => $shop]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $flower = new Flower();
        $flower->flower_sn = sn_20();
        $flower->shop_id = $request->shop_id;
        $flower->flower_name = $request->flower_name;
        $flower->flower_img = $request->flower_img;
        $flower->flower_img_thumb = $request->flower_img.'?imageView2/1/w/400/h/300/q/75|imageslim';
        $flower->flower_number = $request->flower_number;
        $flower->price = $request->price;
        $flower->flower_brief = $request->flower_brief;
        $flower->status = $request->status;
        $flower->sort = $request->sort;
        $flower->integral = $request->integral;
        $flower->give_integral = $request->give_integral;
        $flower->rank_integral = $request->rank_integral;
        $flower->virtual_sales = $request->virtual_sales;
        $flower->color = $request->color;
        $flower->kind = $request->kind;
        $flower->number = $request->number;
        $flower->save();
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
        $info = Flower::find($id);
        $shop = Shop::where('shop_type_id', 3)->get();
        return view('flower.flower_update', ['data' => $info, 'shop' => $shop]);
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
        $flower = Flower::find($id);
        $flower->flower_sn = sn_20();
        $flower->shop_id = $request->shop_id;
        $flower->flower_name = $request->flower_name;
        $flower->flower_img = $request->flower_img;
        $flower->flower_img_thumb = $request->flower_img.'?imageView2/1/w/400/h/300/q/75|imageslim';
        $flower->flower_number = $request->flower_number;
        $flower->price = $request->price;
        $flower->flower_brief = $request->flower_brief;
        $flower->status = $request->status;
        $flower->sort = $request->sort;
        $flower->integral = $request->integral;
        $flower->give_integral = $request->give_integral;
        $flower->rank_integral = $request->rank_integral;
        $flower->virtual_sales = $request->virtual_sales;
        $flower->color = $request->color;
        $flower->kind = $request->kind;
        $flower->number = $request->number;
        $flower->save();
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
        $info = Flower::find($id);
        $info->delete();
        admin_log('删除了ID为 '.$id.' 的花束');
        return status(200, '删除成功');
    }


    // 花束的上下架
    public function flower_status (Request $request){
        $flower = Flower::find($request->id);
        if($flower->status == 1){
            $flower->status = 2;
            $msg = '花艺已下架';
        }else{
            $flower->status = 1;
            $msg = '花艺已上架';
        }
        $flower->save();
        admin_log('修改了id为 '.$request->id.' 的花艺上下架状态');
        return status(200, $msg, $flower->status);
    }


    // 花束回收站
    public function flower_recycle (Request $request){
        if($request->ajax()){
            $page = $request->page;
            $limit = $request->limit;
            $num = ($page-1)*$limit;
            $list = Flower::onlyTrashed()->where('flower_name', 'like', '%'.request('flower_name').'%')->offset($num)->limit($limit)->get();
            $data = [
                'code' => 0,
                'msg' => 'ok',
                'count' => Flower::onlyTrashed()->where('flower_name', 'like', '%'.request('flower_name').'%')->count(),
                'data' => $list
            ];
            return response()->json($data);
        }
        return view('flower.flower_recycle');
    }

    // 永久删除管理员
    public function flower_del (Request $request){
        $flower = Flower::onlyTrashed()->find($request->id);
        $flower->forceDelete();
        admin_log('永久删除id为 '.$request->id.' 的花束');
        return status('200', '删除成功');
    }

    // 回复删除的商品
    public function flower_recover (Request $request){
        $flower = Flower::onlyTrashed()->find($request->id);
        $flower->restore();
        admin_log('恢复了id为 '.$request->id.' 的花束');
        return status('200', '恢复成功');
    }
}
