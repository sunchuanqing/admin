<?php

namespace App\Http\Controllers\Banner;

use App\Models\Banner_type;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Banner_typeController extends Controller
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
            $list = Banner_type::where('name', 'like', '%'.request('name').'%')->offset($num)->limit($limit)->get();
            $data = [
                'code' => 0,
                'msg' => 'ok',
                'count' => Banner_type::where('name', 'like', '%'.request('name').'%')->count(),
                'data' => $list
            ];
            return response()->json($data);
        }
        return view('banner.banner_type');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('banner.banner_type_add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Banner_type::where('name', $request->name)->count() == 0){
            $banner_type = new Banner_type();
            $banner_type->name = $request->name;
            $banner_type->banner_width = $request->banner_width;
            $banner_type->banner_height = $request->banner_height;
            $banner_type->banner_desc = $request->banner_desc;
            $banner_type->save();
            admin_log('添加了轮播图类别 '.$request->name);
            return status(200, '添加成功');
        }else{
            return status(400, '类别名称已存在');
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
        $info = Banner_type::find($id);
        return view('banner.banner_type_update', ['data' => $info]);
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
        $banner_type = Banner_type::find($id);
        if($banner_type->name == $request->name){
            $banner_type->name = $request->name;
        }else{
            if(Banner_type::where('name', $request->name)->count() == 0){
                $banner_type->name = $request->name;
            }else{
                return status(400, '类别名称已存在');
            }
        }
        $banner_type->banner_width = $request->banner_width;
        $banner_type->banner_height = $request->banner_height;
        $banner_type->banner_desc = $request->banner_desc;
        $banner_type->save();
        admin_log('修改了ID为 '.$id.' 的轮播图类别');
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
        $flight = Banner_type::find($id);
        $flight->delete();
        admin_log('删除了ID为 '.$id.' 的轮播图类别');
        return status(200, '删除成功');
    }
}
