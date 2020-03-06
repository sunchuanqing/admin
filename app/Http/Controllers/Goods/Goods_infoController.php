<?php

namespace App\Http\Controllers\Goods;

use App\Models\Goods_info;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Goods_infoController extends Controller
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
            $list = Goods_info::where('goods_id', $request->goods_id)->orderBy('sort', 'asc')->offset($num)->limit($limit)->get();
            $data = [
                'code' => 0,
                'msg' => 'ok',
                'count' => Goods_info::where('goods_id', $request->goods_id)->count(),
                'data' => $list
            ];
            return response()->json($data);
        }
        return view('goods_info.goods_info', ['goods_id' => $request->goods_id]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('goods_info.goods_info_add', ['goods_id' => $request->goods_id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $goods_photo = new Goods_info();
        $goods_photo->goods_id = $request->goods_id;
        $goods_photo->img = $request->goods_info;
        $goods_photo->sort = $request->sort;
        $goods_photo->save();
        admin_log('给门店ID为 '.$request->goods_id.' 的添加了商品详情图片');
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
        $info = Goods_info::find($id, ['id', 'goods_id', 'img', 'sort']);
        return view('goods_info.goods_info_update', ['data' => $info]);
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
        $goods_photo = Goods_info::find($id);
        $goods_photo->img = $request->goods_info;
        $goods_photo->sort = $request->sort;
        $goods_photo->save();
        admin_log('给门店ID为 '.$goods_photo->goods_id.' 的修改了商品详情图片');
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
        $info = Goods_info::find($id);
        $info->delete();
        admin_log('删除了ID为 '.$id.' 的商品详情照片');
        return status(200, '删除成功');
    }
}
