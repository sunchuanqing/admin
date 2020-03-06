<?php

namespace App\Http\Controllers\Topic;

use App\Models\Topic;
use App\Models\Topic_type;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TopicController extends Controller
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
            $list = Topic::where('topic_name', 'like', '%'.request('topic_name').'%')
                ->orderBy('is_top', 'desc')
                ->orderBy('sort', 'desc')
                ->orderBy('id', 'desc')
                ->offset($num)
                ->limit($limit)
                ->get();
            $data = [
                'code' => 0,
                'msg' => 'ok',
                'count' => Topic::where('topic_name', 'like', '%'.request('topic_name').'%')->count(),
                'data' => $list
            ];
            return response()->json($data);
        }
        return view('topic.topic');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $topic_type = Topic_type::get();
        return view('topic.topic_add', ['topic_type' => $topic_type]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $topic = new Topic();
        $topic->topic_name = $request->topic_name;
        $topic->topic_img = $request->topic_img;
        $topic->topic_info_img = $request->topic_info_img;
        $topic->goods_type = $request->goods_type;
        $topic->goods_id = $request->goods_id;
        $topic->sort = $request->sort;
        $topic->status = $request->status;
        $topic->is_top = $request->is_top;
        $topic->topic_brief = $request->topic_brief;
        $topic->route = $request->route;
        $topic->save();
        admin_log('添加了专题活动 '.$request->topic_name);
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
        $topic_type = Topic_type::get();
        $info = Topic::find($id);
        return view('topic.topic_update', ['data' => $info, 'topic_type' => $topic_type]);
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
        $topic = Topic::find($id);
        $topic->topic_name = $request->topic_name;
        $topic->topic_img = $request->topic_img;
        $topic->topic_info_img = $request->topic_info_img;
        $topic->goods_type = $request->goods_type;
        $topic->goods_id = $request->goods_id;
        $topic->sort = $request->sort;
        $topic->status = $request->status;
        $topic->is_top = $request->is_top;
        $topic->topic_brief = $request->topic_brief;
        $topic->route = $request->route;
        $topic->save();
        admin_log('修改了专题活动 '.$request->topic_name);
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
        $flight = Topic::find($id);
        $flight->delete();
        admin_log('删除了ID为 '.$id.' 的专题活动');
        return status(200, '删除成功');
    }
}
