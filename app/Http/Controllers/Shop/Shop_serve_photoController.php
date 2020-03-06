<?php

namespace App\Http\Controllers\Shop;

use App\Models\Shop_serve;
use App\Models\Shop_serve_photo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Shop_serve_photoController extends Controller
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
        $shop_id = Shop_serve::find($request->id)->shop_id;
        return view('shop_serve_photo.shop_serve_photo_add', ['shop_serve_id' => $request->id, 'shop_id' => $shop_id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $shop_serve_photo = new Shop_serve_photo();
        $shop_serve_photo->shop_serve_id = $request->shop_serve_id;
        $shop_serve_photo->serve_thumb = $request->shop_serve_photo.'?imageView2/2/w/200/h/150/interlace/0/q/100';
        $shop_serve_photo->serve_img = $request->shop_serve_photo.'?imageView2/1/w/400/h/300/q/75|imageslim';
        $shop_serve_photo->original_img = $request->shop_serve_photo;
        $shop_serve_photo->sort = $request->sort;
        $shop_serve_photo->save();
        admin_log('给项目ID为 '.$request->shop_serve_id.' 的添加了项目照片');
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
            $list = Shop_serve_photo::where('shop_serve_id', $id)->orderBy('sort', 'desc')->offset($num)->limit($limit)->get();
            $data = [
                'code' => 0,
                'msg' => 'ok',
                'count' => Shop_serve_photo::where('shop_serve_id', $id)->count(),
                'data' => $list
            ];
            return response()->json($data);
        }
        $shop_id = Shop_serve::find($id)->shop_id;
        return view('shop_serve_photo.shop_serve_photo', ['shop_serve_id' => $id, 'shop_id' => $shop_id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $info = Shop_serve_photo::find($id, ['id', 'shop_serve_id', 'serve_thumb', 'original_img', 'sort']);
        $shop_id = Shop_serve::find($id)->shop_id;
        return view('shop_serve_photo.shop_serve_photo_update', ['data' => $info, 'shop_id' => $shop_id]);
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
        $shop_serve_photo = Shop_serve_photo::find($id);
        $shop_serve_photo->serve_thumb = $request->shop_serve_photo.'?imageView2/2/w/200/h/150/interlace/0/q/100';
        $shop_serve_photo->serve_img = $request->shop_serve_photo.'?imageView2/1/w/400/h/300/q/75|imageslim';
        $shop_serve_photo->original_img = $request->shop_serve_photo;
        $shop_serve_photo->sort = $request->sort;
        $shop_serve_photo->save();
        admin_log('修改了ID为 '.$shop_serve_photo->shop_serve_id.' 的项目照片');
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
        $flight = Shop_serve_photo::find($id);
        $flight->delete();
        admin_log('删除了ID为 '.$id.' 的项目照片');
        return status(200, '删除成功');
    }
}
