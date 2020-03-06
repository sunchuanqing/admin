<?php

namespace App\Http\Controllers\Topic;

use App\Models\Topic;
use App\Models\Topic_type;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Topic_typeController extends Controller
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
            $list = Topic_type::where('topic_type_name', 'like', '%'.request('topic_type_name').'%')
                ->orderBy('sort','desc')
                ->orderBy('id','desc')
                ->offset($num)
                ->limit($limit)
                ->get();
            $data = [
                'code' => 0,
                'msg' => 'ok',
                'count' => Topic_type::where('topic_type_name', 'like', '%'.request('topic_type_name').'%')->count(),
                'data' => $list
            ];
            return response()->json($data);
        }
        return view('topic_type.topic_type');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('topic_type.topic_type_add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Topic_type::where('topic_type_name', $request->topic_type_name)->count() == 0){
            $topic_type = new Topic_type();
            $topic_type->topic_type_name = $request->topic_type_name;
            $topic_type->sort = $request->sort;
            $topic_type->save();
            admin_log('添加专题类别 '.$request->topic_type_name);
            return status(200, '添加成功');
        }else{
            return status(400, '专题类别已存在');
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
        $info = Topic_type::find($id);
        return view('topic_type.topic_type_update', ['data' => $info]);
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
        $topic_type = Topic_type::find($id);
        if($topic_type->topic_type_name == $request->topic_type_name){
            $topic_type->sort = $request->sort;
            $topic_type->save();
            return status(200, '修改成功');
        }else{
            if(Topic_type::where('topic_type_name', $request->topic_type_name)->count() == 0){
                $topic_type->topic_type_name = $request->topic_type_name;
                $topic_type->sort = $request->sort;
                $topic_type->save();
                admin_log('修改了ID为 '.$id.' 的专题类别');
                return status(200, '修改成功');
            }else{
                return status(400, '专题类别已存在');
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
        if(Topic::where('topic_type_id', $id)->count() == 0){
            $flight = Topic_type::find($id);
            $flight->delete();
            admin_log('删除了ID为 '.$id.' 的专题类别');
            return status(200, '删除成功');
        }else{
            return status(400, '类别含有专题活动不许删除');
        }
    }
}
