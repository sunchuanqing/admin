<?php

namespace App\Http\Controllers\Shop;

use App\Models\Shop_photo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Shop_photoController extends Controller
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
        return view('shop_photo.shop_photo_add', ['shop_id' => $request->id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $shop_photo = new Shop_photo();
        $shop_photo->shop_id = $request->shop_id;
        $shop_photo->shop_thumb = $request->shop_photo.'?imageView2/2/w/200/h/150/interlace/0/q/100';
        $shop_photo->shop_img = $request->shop_photo.'?imageView2/1/w/400/h/300/q/75|imageslim';
        $shop_photo->original_img = $request->shop_photo;
        $shop_photo->sort = $request->sort;
        $shop_photo->save();
        admin_log('给门店ID为 '.$request->shop_id.' 的添加了门店照片');
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
            $list = Shop_photo::where('shop_id', $id)->orderBy('sort', 'desc')->offset($num)->limit($limit)->get();
            $data = [
                'code' => 0,
                'msg' => 'ok',
                'count' => Shop_photo::where('shop_id', $id)->count(),
                'data' => $list
            ];
            return response()->json($data);
        }
        return view('shop_photo.shop_photo', ['shop_id' => $id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $info = Shop_photo::find($id, ['id', 'shop_id', 'shop_thumb', 'original_img', 'sort']);
        return view('shop_photo.shop_photo_update', ['data' => $info]);
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
        $shop_photo = Shop_photo::find($id);
        $shop_photo->shop_thumb = $request->shop_photo.'?imageView2/2/w/200/h/150/interlace/0/q/100';
        $shop_photo->shop_img = $request->shop_photo.'?imageView2/1/w/400/h/300/q/75|imageslim';
        $shop_photo->original_img = $request->shop_photo;
        $shop_photo->sort = $request->sort;
        $shop_photo->save();
        admin_log('给门店ID为 '.$shop_photo->shop_id.' 的门店照片');
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
        $flight = Shop_photo::find($id);
        $flight->delete();
        admin_log('删除了ID为 '.$id.' 的门店照片');
        return status(200, '删除成功');
    }
}
