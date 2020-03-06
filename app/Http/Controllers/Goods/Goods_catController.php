<?php

namespace App\Http\Controllers\Goods;

use App\Models\Category;
use App\Models\Good;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Goods_catController extends Controller
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
            $list = Category::where('name', 'like', '%'.request('name').'%')->offset($num)->limit($limit)->get()->toFlatTree();
            foreach ($list as $k => $v) {
                $str = '';
                $int = Category::withDepth()->find($v->id)->depth;
                for ($x = 0; $x < $int; $x++) {
                    $str .= '--- ';
                }
                $list[$k]['names'] = $str;// 给页面组装
                $list[$k]['namess'] = $str;// 修改时组装
                $list[$k]['number'] = Good::where('cat_id', $v->id)->count();
            }
            $data = [
                'code' => 0,
                'msg' => 'ok',
                'count' => Category::where('name', 'like', '%'.request('name').'%')->count(),
                'data' => $list
            ];
            return response()->json($data);
        }
        return view('goods_cat.goods_cat');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = Category::where('parent_id', null)->get();
        return view('goods_cat.goods_cat_add', ['data' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attributes = ['name' => $request->name];
        $parent = Category::find($request->parent_id);
        Category::create($attributes, $parent);
        admin_log('添加商品分类 '.$request->name);
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
        //
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
        $category = Category::find($id);
        $name = $category->name;
        $category->name = $request->name;
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
        $category = Category::find($id);
        if(Category::where('parent_id', $id)->count()){
            return status(401, '分类含有子类,删除失败');
        }else if(Good::where('cat_id', $id)->count()){
            return status(401, '分类含有商品,删除失败');
        }else{
            $category->delete();
            admin_log('删除商品分类 '.$category->name);
            return status(200, '删除成功');
        }
    }

    // 查看子集元素
    public function cat_child (Request $request){
        if(Category::where('parent_id', $request->parent_id)->count() > 0){
            $data = Category::where('parent_id', $request->parent_id)->get();
            return status(200, '查找成功', $data);
        }else{
            return status(404, '没有子元素');
        }
    }
}
