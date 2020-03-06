<?php

namespace App\Http\Controllers\Goods;

use App\Models\Goods_photo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Goods_photoController extends Controller
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
        return view('goods_photo.goods_photo_add', ['goods_id' => $request->goods_id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $goods_photo = new Goods_photo();
        $goods_photo->goods_id = $request->goods_id;
        $goods_photo->goods_thumb = $request->goods_photo.'?imageView2/2/w/300/h/300/interlace/0/q/100';
        $goods_photo->goods_img = $request->goods_photo.'?imageView2/1/w/500/h/500/q/75|imageslim';
        $goods_photo->original_img = $request->goods_photo;
        $goods_photo->sort = $request->sort;
        $goods_photo->save();
        admin_log('给门店ID为 '.$request->goods_id.' 的添加了商品照片');
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
            $list = Goods_photo::where('goods_id', $id)->orderBy('sort', 'desc')->offset($num)->limit($limit)->get();
            $data = [
                'code' => 0,
                'msg' => 'ok',
                'count' => Goods_photo::where('goods_id', $id)->count(),
                'data' => $list
            ];
            return response()->json($data);
        }
        return view('goods_photo.goods_photo', ['goods_id' => $id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $info = Goods_photo::find($id, ['id', 'goods_id', 'goods_thumb', 'original_img', 'sort']);
        return view('goods_photo.goods_photo_update', ['data' => $info]);
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
        $goods_photo = Goods_photo::find($id);
        $goods_photo->goods_thumb = $request->goods_photo.'?imageView2/2/w/300/h/300/interlace/0/q/100';
        $goods_photo->goods_img = $request->goods_photo.'?imageView2/1/w/400/h/400/q/75|imageslim';
        $goods_photo->original_img = $request->goods_photo;
        $goods_photo->sort = $request->sort;
        $goods_photo->save();
        admin_log('给门店ID为 '.$goods_photo->goods_id.' 的门店照片');
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
        $info = Goods_photo::find($id);
        $info->delete();
        admin_log('删除了ID为 '.$id.' 的商品照片');
        return status(200, '删除成功');
    }
}
