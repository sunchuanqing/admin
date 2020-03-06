<?php

namespace App\Http\Controllers\Goods;

use App\Models\Good;
use App\Models\Goods_attribute;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class Goods_attributeController extends Controller
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
            $list = Goods_attribute::where('goods_id', request('goods_id'))->where('attr_name', 'like', '%'.request('attr_name').'%')->orderBy('attr_sort', 'desc')->offset($num)->limit($limit)->get();
            $data = [
                'code' => 0,
                'msg' => 'ok',
                'count' => Goods_attribute::where('attr_name', 'like', '%'.request('attr_name').'%')->count(),
                'data' => $list
            ];
            return response()->json($data);
        }
        $goods = Good::find($request->goods_id);
        return view('goods_attribute.goods_attribute', ['goods' => $goods]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('goods_attribute.goods_attribute_add', ['goods_id' => $request->goods_id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = new Goods_attribute();
        $data->goods_id = $request->goods_id;
        $data->attr_name = $request->attr_name;
        if($request->attr_money >= 0){
            $data->attr_money = $request->attr_money;
        }else{
            return status(400, '属性价格不合法');
        }
        if($request->attr_number >= 0){
            $data->attr_number = $request->attr_number;
        }else{
            return status(400, '属性库存不合法');
        }
        $data->attr_thumb = $request->attr_img.'?imageView2/2/w/200/h/150/interlace/0/q/100';
        $data->attr_img = $request->attr_img.'?imageView2/1/w/400/h/300/q/75|imageslim';
        $data->attr_original_img = $request->attr_img;
        $data->attr_sort = $request->attr_sort;
        $goods = Good::find($request->goods_id);
        $goods->goods_number = $goods->goods_number + $request->attr_number;
        DB::beginTransaction();
        try {
            $data->save();
            $goods->save();
            DB::commit();
            admin_log('给商品 '.$goods->goods_name.' 添加商品属性：'.$request->attr_name);
            return status(200, '添加成功');
        } catch (QueryException $ex) {
            DB::rollback();
            return status(400, '添加失败');
        }
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
        $data = Goods_attribute::find($id);
        return view('goods_attribute.goods_attribute_update', ['data' => $data]);
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
        $data = Goods_attribute::find($id);
        $data->attr_name = $request->attr_name;
        if($request->attr_money >= 0){
            $data->attr_money = $request->attr_money;
        }else{
            return status(400, '属性价格不合法');
        }
        if($request->attr_number >= 0){
            $number = $request->attr_number - $data->attr_number;
            $data->attr_number = $request->attr_number;
        }else{
            return status(400, '属性库存不合法');
        }
        $data->attr_thumb = $request->attr_img.'?imageView2/2/w/200/h/150/interlace/0/q/100';
        $data->attr_img = $request->attr_img.'?imageView2/1/w/400/h/300/q/75|imageslim';
        $data->attr_original_img = $request->attr_img;
        $data->attr_sort = $request->attr_sort;
        $goods = Good::find($data->goods_id);
        $goods->goods_number = $goods->goods_number + $number;
        DB::beginTransaction();
        try {
            $data->save();
            $goods->save();
            DB::commit();
            admin_log('给商品 '.$goods->goods_name.' 修改商品属性：'.$request->attr_name);
            return status(200, '修改成功');
        } catch (QueryException $ex) {
            DB::rollback();
            return status(400, '修改失败');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $goods_attribute = Goods_attribute::find($id);
        $goods = Good::find($goods_attribute->goods_id);
        $goods_number = $goods->goods_number - $goods_attribute->attr_number;
        DB::beginTransaction();
        try {
            $goods->goods_number = $goods_number;
            $goods->save();
            $goods_attribute->delete();
            DB::commit();
            admin_log('删除了商品 '.$goods->goods_name.' 的属性 '.$goods_attribute->attr_name);
            return status(200, '删除成功');
        } catch (QueryException $ex) {
            DB::rollback();
            return status(400, '删除失败');
        }
    }

    // 商品库存变化
    public function goods_number (Request $request){
        $goods_attribute = Goods_attribute::find($request->id);
        $goods = Good::find($goods_attribute->goods_id);
        $attr_number = $goods_attribute->attr_number + $request->attr_number;
        if($attr_number < 0){
            return status(400, '数量有误');
        }
        $goods_number = $goods->goods_number + $request->attr_number;
        DB::beginTransaction();
        try {
            $goods_attribute->attr_number = $attr_number;
            $goods->goods_number = $goods_number;
            $goods_attribute->save();
            $goods->save();
            DB::commit();
            admin_log('给商品 '.$goods->goods_name.' 的属性 '.$goods_attribute->attr_name.' 入库 '.$request->attr_number.' 件');
            return status(200, '入库成功', $attr_number);
        } catch (QueryException $ex) {
            DB::rollback();
            return status(400, '入库失败');
        }
    }
}
