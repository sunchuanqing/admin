<?php

namespace App\Http\Controllers\Flower;

use App\Models\Flower_photo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Flower_photoController extends Controller
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
        return view('flower_photo.flower_photo_add', ['flower_id' => $request->flower_id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $goods_photo = new Flower_photo();
        $goods_photo->flower_id = $request->flower_id;
        $goods_photo->img = $request->flower_photo;
        $goods_photo->sort = $request->sort;
        $goods_photo->save();
        admin_log('给花束ID为 '.$request->flower_id.' 的添加了花束照片');
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
            $list = Flower_photo::where('flower_id', $id)->orderBy('sort', 'desc')->offset($num)->limit($limit)->get();
            $data = [
                'code' => 0,
                'msg' => 'ok',
                'count' => Flower_photo::where('flower_id', $id)->count(),
                'data' => $list
            ];
            return response()->json($data);
        }
        return view('flower_photo.flower_photo', ['flower_id' => $id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $info = Flower_photo::find($id, ['id', 'flower_id', 'img', 'sort']);
        return view('flower_photo.flower_photo_update', ['data' => $info]);
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
        $goods_photo = Flower_photo::find($id);
        $goods_photo->img = $request->flower_photo;
        $goods_photo->sort = $request->sort;
        $goods_photo->save();
        admin_log('修改了花束ID为 '.$goods_photo->flower_id.' 的照片');
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
        $info = Flower_photo::find($id);
        $info->delete();
        admin_log('删除了ID为 '.$id.' 的花束照片');
        return status(200, '删除成功');
    }
}
