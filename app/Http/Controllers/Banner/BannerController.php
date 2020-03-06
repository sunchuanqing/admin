<?php

namespace App\Http\Controllers\Banner;

use App\Models\Banner;
use App\Models\Banner_type;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BannerController extends Controller
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
            if(request('type_id')){
                $list = Banner::where('name', 'like', '%'.request('name').'%')->where('type_id', request('type_id'))->offset($num)->limit($limit)->get();
                $count = Banner::where('name', 'like', '%'.request('name').'%')->where('type_id', request('type_id'))->count();
            }else{
                $list = Banner::where('name', 'like', '%'.request('name').'%')->offset($num)->limit($limit)->get();
                $count = Banner::where('name', 'like', '%'.request('name').'%')->count();
            }
            $data = [
                'code' => 0,
                'msg' => 'ok',
                'count' => $count,
                'data' => $list
            ];
            return response()->json($data);
        }
        $banner_type = Banner_type::get();
        return view('banner.banner', ['banner_type' => $banner_type]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $banner_type = Banner_type::get();
        return view('banner.banner_add', ['banner_type' => $banner_type]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $banner = new Banner();
        $type_name = Banner_type::find($request->type_id);
        $banner->type_id = $request->type_id;
        $banner->type_name = $type_name['name'];
        $banner->name = $request->name;
        $banner->img_url = $request->img_url;
        $banner->link = $request->link;
        if($request->end_time>$request->start_time){
            $banner->start_time = $request->start_time;
            $banner->end_time = $request->end_time;
        }else{
            return status(400, '时间输入不正确');
        }
        $banner->status = $request->status;
        $banner->save();
        admin_log('添加了轮播图 '.$request->name);
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
        $banner_type = Banner_type::get();
        $info = Banner::find($id);
        $type = Banner_type::find($info->type_id);
        return view('banner.banner_update', ['data' => $info, 'banner_type' => $banner_type, 'type' => $type]);
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
        $banner = Banner::find($id);
        $type_name = Banner_type::find($request->type_id);
        $banner->type_id = $request->type_id;
        $banner->type_name = $type_name['name'];
        $banner->name = $request->name;
        $banner->img_url = $request->img_url;
        $banner->link = $request->link;
        if($request->end_time>$request->start_time){
            $banner->start_time = $request->start_time;
            $banner->end_time = $request->end_time;
        }else{
            return status(400, '时间输入不正确');
        }
        $banner->status = $request->status;
        $banner->save();
        admin_log('修改了ID为 '.$id.' 轮播图 ');
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
        $flight = Banner::find($id);
        $flight->delete();
        admin_log('删除了ID为 '.$id.' 的轮播图');
        return status(200, '删除成功');
    }

    // 修改轮播图状态
    public function banner_status (Request $request){
        $banner = Banner::find($request->id);
        if($banner->status == 1){
            $banner->status = 2;
            $msg = '禁用';
        }else{
            $banner->status = 1;
            $msg = '启用';
        }
        $banner->save();
        admin_log($msg.'了id为 '.$request->id.' 轮播图');
        return status(200, $msg);
    }
}
