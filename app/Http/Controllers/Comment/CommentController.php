<?php

namespace App\Http\Controllers\Comment;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentController extends Controller
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
            $list = Comment::where('comment_sn', 'like', '%'.request('comment_sn').'%')
                ->join('shops', 'comments.shop_id', '=', 'shops.id')
                ->offset($num)
                ->limit($limit)
                ->select(['comments.id', 'comment_sn', 'user_name', 'shop_id', 'comments.thumb_img', 'comment_img', 'comment_content', 'is_img', 'is_hot', 'is_top', 'is_recommend', 'status', 'comments.created_at', 'shops.shop_name'])
                ->get();
            $data = [
                'code' => 0,
                'msg' => 'ok',
                'count' => Comment::where('comment_sn', 'like', '%'.request('comment_sn').'%')->count(),
                'data' => $list
            ];
            return response()->json($data);
        }
        return view('comment.comment');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('comment.comment_add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $comment = new Comment();
        $comment->comment_sn = sn_20();
        $comment->user_id = 10;
        $comment->user_name = '嘉然集团';
        $comment->user_photo = 'http://img.jiaranjituan.cn/photo.png';
        $comment->thumb_img = $request->img_url;
        $comment->comment_img = $request->img_url;
        $comment->original_img = $request->img_url;
        $comment->comment_content = $request->comment_content;
        $comment->is_img = 2;
        $comment->is_admin = 2;
        $comment->save();
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function comment_hot (Request $request){
        $comment = Comment::find($request->id);
        if($comment->is_hot == 1){
            $comment->is_hot = 2;
            $msg = '已设为热门';
        }else{
            $comment->is_hot = 1;
            $msg = '取消为热门';
        }
        $comment->save();
        admin_log('修改了id为 '.$request->id.' 的晒一晒热门状态');
        return status(200, $msg, $comment->is_hot);
    }


    public function comment_top (Request $request){
        $comment = Comment::find($request->id);
        if($comment->is_top == 1){
            $comment->is_top = 2;
            $msg = '已设为置顶';
        }else{
            $comment->is_top = 1;
            $msg = '取消为置顶';
        }
        $comment->save();
        admin_log('修改了id为 '.$request->id.' 的晒一晒置顶状态');
        return status(200, $msg, $comment->is_top);
    }


    public function comment_recommend (Request $request){
        $comment = Comment::find($request->id);
        if($comment->is_recommend == 1){
            $comment->is_recommend = 2;
            $msg = '已设为推荐';
        }else{
            $comment->is_recommend = 1;
            $msg = '取消为推荐';
        }
        $comment->save();
        admin_log('修改了id为 '.$request->id.' 的晒一晒推荐状态');
        return status(200, $msg, $comment->is_recommend);
    }

    public function comment_status (Request $request){
        $comment = Comment::find($request->id);
        if($comment->status == 1){
            $comment->status = 2;
            $msg = '隐藏';
        }else{
            $comment->status = 1;
            $msg = '显示';
        }
        $comment->save();
        admin_log('修改了id为 '.$request->id.' 的晒一晒状态');
        return status(200, $msg, $comment->status);
    }
}
