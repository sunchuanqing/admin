<?php

namespace App\Http\Controllers\Shop;

use App\Models\Price_list;
use App\Models\Price_type;
use App\Models\Shop_serve;
use App\Models\Shop_serve_type;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Shop_priceController extends Controller
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
        $data = Price_type::where('shop_id', $request->shop_id)->where('parent_id', null)->get();
        return view('shop_price.shop_price_add', ['shop_id' => $request->shop_id, 'data' => $data]);
    }

    // 查询子类
    public function shop_price_child (Request $request){
        if(Price_type::where('parent_id', $request->parent_id)->count() > 0){
            $data = Price_type::where('parent_id', $request->parent_id)->get();
            return status(200, '查找成功', $data);
        }else{
            return status(404, '没有子元素');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attributes = ['name' => $request->name, 'img' => $request->img, 'brief' => $request->brief, 'price_info' => $request->price_info, 'shop_id' => $request->shop_id];
        $parent = Price_type::find($request->parent_id);
        Price_type::create($attributes, $parent);
        admin_log('添加商品分类 '.$request->name);
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
            $list = Price_type::where('shop_id', $id)->where('name', 'like', '%'.request('name').'%')->offset($num)->limit($limit)->get()->toFlatTree();
            foreach ($list as $k => $v) {
                $str = '';
                $int = Price_type::withDepth()->find($v->id)->depth;
                for ($x = 0; $x < $int; $x++) {
                    $str .= '--- ';
                }
                $list[$k]['names'] = $str;// 给页面组装
                $list[$k]['namess'] = $str;// 修改时组装
            }
            $data = [
                'code' => 0,
                'msg' => 'ok',
                'count' => Price_type::where('shop_id', $id)->where('name', 'like', '%'.request('name').'%')->count(),
                'data' => $list
            ];
            return response()->json($data);
        }
        return view('shop_price.shop_price', ['shop_id' => $id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Price_type::find($id);
        return view('shop_price.shop_price_update', ['data' => $data]);
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
        $category = Price_type::find($id);
        $name = $category->name;
        $category->name = $request->name;
        $category->img = $request->img;
        $category->brief = $request->brief;
        $category->price_info = $request->price_info;
        $category->save();
        admin_log('修改商品分类 '.$name.' 为 '.$request->name);
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
        $category = Price_type::find($id);
        if(Price_type::where('parent_id', $id)->count()){
            return status(401, '分类含有子类,删除失败');
        }else{
            $category->delete();
            admin_log('删除商品分类 '.$category->name);
            return status(200, '删除成功');
        }
    }


    // 价目表列表
    public function shop_price_list (Request $request){
        if($request->ajax()){
            $page = $request->page;
            $limit = $request->limit;
            $num = ($page-1)*$limit;
            $list = Price_list::where('price_list_type_id', '>', 0)->where('shop_id', $request->id)->where('price_list_name', 'like', '%'.request('price_list_name').'%')->orderBy('id', 'desc')->offset($num)->limit($limit)->get();
            foreach ($list as $k => $v){
                $str = '';
                $price_type = Price_type::find($v['price_list_type_id'])['name'];
                $parent = Price_type::ancestorsOf($v['price_list_type_id']);
                if(count($parent) > 0){
                    foreach ($parent as $ks => $vs){
                        $str = $str.$vs['name'].'->';
                    }
                }
                $list[$k]['price_type'] = $str.$price_type;
            }
            $data = [
                'code' => 0,
                'msg' => 'ok',
                'count' => Price_list::where('shop_id', $request->id)->where('price_list_name', 'like', '%'.request('price_list_name').'%')->count(),
                'data' => $list
            ];
            return response()->json($data);
        }
        return view('shop_price.shop_price_list', ['shop_id' => $request->id]);
    }


    // 添加价目表
    public function shop_price_list_add (Request $request){
        if($request->ajax()){
            $price_list = new Price_list();
            $price_list->shop_id = $request->shop_id;
            $price_list->price_sn = sn_20();
            $price_list->price_list_type_id = $request->price_list_type_id;
            $price_list->price_list_name = $request->price_list_name;
            $price_list->price = $request->price;
//            $price_list->vip_price = $request->vip_price;
            $price_list->img = $request->img_url;
            $price_list->rank = $request->rank;
            $price_list->period = $request->period;
            $price_list->man_hour = $request->man_hour;
            $price_list->brief = $request->brief;
            $price_list->sell_money = $request->sell_money;
            $price_list->job_money = $request->job_money;
            $price_list->save();
            return status(200, '添加成功');
        }
        $price_type = Price_type::where('shop_id', $request->id)->where('parent_id', null)->get();
        return view('shop_price.shop_price_list_add', ['price_type' => $price_type, 'shop_id' => $request->id]);
    }


    // 删除价目表
    public function shop_price_list_del (Request $request){
        $flight = Price_list::find($request->id);
        $flight->delete();
        admin_log('删除了ID为 '.$request->id.' 的价目表');
        return status(200, '删除成功');
    }


    // 修改价目表
    public function shop_price_list_update (Request $request){
        if($request->ajax()){
            $price_list = Price_list::find($request->id);
            $price_list->price_list_type_id = $request->price_list_type_id;
            $price_list->price_list_name = $request->price_list_name;
            $price_list->price = $request->price;
//            $price_list->vip_price = $request->vip_price;
            $price_list->img = $request->img_url;
            $price_list->rank = $request->rank;
            $price_list->period = $request->period;
            $price_list->man_hour = $request->man_hour;
            $price_list->brief = $request->brief;
            $price_list->sell_money = $request->sell_money;
            $price_list->job_money = $request->job_money;
            $price_list->save();
            return status(200, '修改成功');
        }
        $info = Price_list::find($request->id);
        $cat = [];
        $data = Price_type::find($info -> price_list_type_id);
        $parent = $data->ancestors;
        foreach ($parent as $k => $v){
            $parend_data = Price_type::where('shop_id', $info['shop_id'])->where('parent_id', $v->parent_id)->get();
            $cat[$k] = $parend_data;
            $cat[$k]['select'] = $v->id;
        }
        $brother = Price_type::where('parent_id', $data->parent_id)->get();
        $brother['select'] = $info -> price_list_type_id;
        array_push($cat, $brother);
        return view('shop_price.shop_price_list_update', ['info' => $info, 'cat' => $cat]);
    }
}
