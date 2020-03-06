<?php

namespace App\Http\Controllers\Shop;

use App\Models\Shop;
use App\Models\Shop_type;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Shop_typeController extends Controller
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
            $list = Shop_type::where('type_name', 'like', '%'.request('type_name').'%')->orderBy('sort','desc')->offset($num)->limit($limit)->get();
            $data = [
                'code' => 0,
                'msg' => 'ok',
                'count' => Shop_type::where('type_name', 'like', '%'.request('type_name').'%')->count(),
                'data' => $list
            ];
            return response()->json($data);
        }
        return view('shoptype.shop_type');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('shoptype.shop_type_add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Shop_type::where('type_name', $request->type_name)->count() == 0){
            $shop_type = new Shop_type();
            $shop_type->type_name = $request->type_name;
            $shop_type->sort = $request->sort;
            $shop_type->save();
            admin_log('添加门店类别 '.$request->type_name);
            return status(200, '添加成功');
        }else{
            return status(400, '门店类别已存在');
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
        $info = Shop_type::find($id);
        return view('shoptype.shop_type_update', ['data' => $info]);
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
        $shop_type = Shop_type::find($id);
        if($shop_type->type_name == $request->type_name){
            $shop_type->sort = $request->sort;
            $shop_type->save();
            return status(200, '修改成功');
        }else{
            if(Shop_type::where('type_name', $request->type_name)->count() == 0){
                $shop_type->type_name = $request->type_name;
                $shop_type->sort = $request->sort;
                $shop_type->save();
                admin_log('修改了ID为 '.$id.' 的门店类别');
                return status(200, '修改成功');
            }else{
                return status(400, '门店类别已存在');
            }
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
        if(Shop::where('shop_type_id', $id)->count() == 0){
            $flight = Shop_type::find($id);
            $flight->delete();
            admin_log('删除了ID为 '.$id.' 的门店类别');
            return status(200, '删除成功');
        }else{
            return status(400, '类别含有门店不许删除');
        }
    }
}
