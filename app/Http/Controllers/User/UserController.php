<?php

namespace App\Http\Controllers\User;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
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
            $list = User::where('user_name', 'like', '%'.request('user_name').'%')->where('phone', 'like', '%'.request('phone').'%')->offset($num)->limit($limit)->orderBy('id', 'desc')->get();
            $data = [
                'code' => 0,
                'msg' => 'ok',
                'count' => User::where('user_name', 'like', '%'.request('user_name').'%')->where('phone', 'like', '%'.request('phone').'%')->count(),
                'data' => $list
            ];
            return response()->json($data);
        }
        return view('user.user');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('user.useradd');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->password == $request->qrpassword){
            $user = new User();
            $user->user_name = $request->user_name;
            if(User::where('phone', $request->phone)->count() == 0){
                $user->phone = $request->phone;
            }else{
                return status(400, '手机号已存在');
            }
            $user->photo = $request->photo;
            $user->sex = $request->sex;
            $user->user_sn = $sn = substr(date('Ymd', time()), 2, 6).mt_rand(1000, 9999);
            $user->flag = $request->flag;
            $user->birthday = $request->birthday;
            $user->last_time = date('Y-m-d H:i:s', time());
            $user->last_ip = $_SERVER["REMOTE_ADDR"];
            $user->save();
            admin_log('添加会员：'.$request->user_name);
            return status(200, '添加成功');
        }else{
            return status(400, '两次输入密码不一致');
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
        $info = User::find($id);
        return view('user.userupdate', ['data' => $info]);
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
        $user = User::find($id);
        $user->user_name = $request->user_name;
        if($user->phone != $request->phone){
            if(User::where('phone', $request->phone)->count() == 0){
                $user->phone = $request->phone;
            }else{
                return status(400, '手机号已存在');
            }
        }
        $user->photo = $request->photo;
        $user->sex = $request->sex;
        $user->flag = $request->flag;
        $user->birthday = $request->birthday;
        $user->save();
        admin_log('修改了ID为 '.$id.' 的会员信息');
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
        $flight = User::find($id);
        $flight->delete();
        admin_log('删除了ID为 '.$id.' 的会员');
        return status(200, '删除成功');
    }

    // 修改会员密码
    public function user_pass (Request $request){
        if($request->ajax()){
            if($request->password == $request->qrpassword){
                $user = User::find($request->id);
                $user->password = md5($request->password);
                $user->save();
                admin_log('修改了id为 '.$request->id.' 的会员密码');
                return status('200', '修改成功');
            }else{
                return status('400', '两次密码输入不一致');
            }
        }
        return view('user.password', ['id' => $request->id]);
    }

    // 会员回收站
    public function user_recycle (Request $request){
        if($request->ajax()){
            $page = $request->page;
            $limit = $request->limit;
            $num = ($page-1)*$limit;
            $list = User::onlyTrashed()->where('user_name', 'like', '%'.request('user_name').'%')->where('phone', 'like', '%'.request('phone').'%')->offset($num)->limit($limit)->get();
            $data = [
                'code' => 0,
                'msg' => 'ok',
                'count' => User::onlyTrashed()->where('user_name', 'like', '%'.request('user_name').'%')->where('phone', 'like', '%'.request('phone').'%')->count(),
                'data' => $list
            ];
            return response()->json($data);
        }
        return view('user.user_recycle');
    }

    // 恢复会员删除
    public function user_recover (Request $request){
        $user = User::onlyTrashed()->find($request->id);
        $user->restore();
        admin_log('永久删除id为 '.$request->id.' 的会员');
        return status('200', '恢复成功');
    }

    // 永久删除会员
    public function user_del (Request $request){
        $user = User::onlyTrashed()->find($request->id);
        $user->forceDelete();
        admin_log('恢复了id为 '.$request->id.' 的会员');
        return status('200', '删除成功');
    }

    // 会员状态修改
    public function flag (Request $request){
        $user = User::find($request->id);
        if($user->flag == 1){
            $user->flag = 2;
            $msg = '禁用';
        }else{
            $user->flag = 1;
            $msg = '启用';
        }
        $user->save();
        admin_log($msg.'了id为 '.$request->id.' 会员');
        return status(200, $msg);
    }
}
