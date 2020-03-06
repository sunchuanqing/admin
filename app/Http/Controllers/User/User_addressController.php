<?php

namespace App\Http\Controllers\User;

use App\Models\User_addresses;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class User_addressController extends Controller
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
            $list = User_addresses::where('name', 'like', '%'.request('name').'%')->where('phone', 'like', '%'.request('phone').'%')->offset($num)->limit($limit)->get();
            $data = [
                'code' => 0,
                'msg' => 'ok',
                'count' => User_addresses::where('name', 'like', '%'.request('name').'%')->where('phone', 'like', '%'.request('phone').'%')->count(),
                'data' => $list
            ];
            return response()->json($data);
        }
        return view('user.user_address');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function user_address_add (Request $request){
        return view('user.user_addressadd', ['user_id' => $request->id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_address = new User_addresses();
        $user_address->user_id = $request->user_id;
        $user_address->name = $request->name;
        $user_address->phone = $request->phone;
        $user_address->country = 86;
        $user_address->province = $request->province;
        $user_address->city = $request->city;
        $user_address->district = $request->district;
        $user_address->street = $request->street;
        $user_address->address = $request->address;
        $user_address->zipcode = $request->zipcode;
        $user_address->save();
        admin_log('添加会员id为 '.$request->user_id.' 的收货地址');
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
        $info = User_addresses::find($id);
        return view('user.user_addressupdate', ['data' => $info]);
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
        $user_address = User_addresses::find($id);
        $user_address->name = $request->name;
        $user_address->phone = $request->phone;
        $user_address->province = $request->province;
        $user_address->city = $request->city;
        $user_address->district = $request->district;
        $user_address->street = $request->street;
        $user_address->address = $request->address;
        $user_address->zipcode = $request->zipcode;
        $user_address->save();
        admin_log('修改了id为 '.$id.' 的收货地址信息');
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
        $flight = User_addresses::find($id);
        $flight->delete();
        admin_log('删除了ID为 '.$id.' 的地址');
        return status(200, '删除成功');
    }

    // 查看单个会员的地址
    public function user_address_only(Request $request)
    {
        if($request->ajax()){
            $page = $request->page;
            $limit = $request->limit;
            $num = ($page-1)*$limit;
            $list = User_addresses::where('user_id', $request->id)->where('name', 'like', '%'.request('name').'%')->where('phone', 'like', '%'.request('phone').'%')->offset($num)->limit($limit)->get();
            $data = [
                'code' => 0,
                'msg' => 'ok',
                'count' => User_addresses::where('user_id', $request->id)->where('name', 'like', '%'.request('name').'%')->where('phone', 'like', '%'.request('phone').'%')->count(),
                'data' => $list
            ];
            return response()->json($data);
        }
        return view('user.user_address_only', ['user_id' => $request->id]);
    }
}
