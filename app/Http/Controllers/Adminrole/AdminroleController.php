<?php

namespace App\Http\Controllers\Adminrole;

use App\Models\Admin_role;
use App\Models\Shop;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminroleController extends Controller
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
            $list = Admin_role::where('role_name', 'like', '%'.request('role_name').'%')
                ->where('shop_name', 'like', '%'.request('shop_name').'%')
                ->join('shops', 'admin_roles.shop_id', '=', 'shops.id')
                ->join('shop_types', 'shops.shop_type_id', '=', 'shop_types.id')
                ->orderBy('admin_roles.id', 'desc')
                ->offset($num)
                ->limit($limit)
                ->select(['admin_roles.id', 'admin_roles.role_name', 'shop_types.type_name', 'shops.shop_name'])
                ->get();
            $data = [
                'code' => 0,
                'msg' => 'ok',
                'count' => Admin_role::where('role_name', 'like', '%'.request('role_name').'%')->count(),
                'data' => $list
            ];
            return response()->json($data);
        }
        return view('adminrole.admin_role');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $shop = Shop::get();
        return view('adminrole.admin_role_add', ['shop' => $shop]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Admin_role::where('shop_id', $request->shop_id)->where('role_name', $request->role_name)->count() == 0){
            $admin_role = new Admin_role();
            $admin_role->shop_id = $request->shop_id;
            $admin_role->role_name = $request->role_name;
            $admin_role->save();
            admin_log('添加员工端角色 '.$request->role_name);
            return status(200, '添加成功');
        }else{
            return status(400, '角色名称已存在');
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
        $info = Admin_role::find($id);
        $shop = Shop::get();
        return view('adminrole.admin_role_update', ['data' => $info, 'shop' => $shop]);
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
        $admin_role = Admin_role::find($id);
        if(Admin_role::where('shop_id', $request->shop_id)->where('role_name', $request->role_name)->count() == 0){
            $admin_role->role_name = $request->role_name;
        }else if($admin_role->role_name != $request->role_name){
            return status(400, '角色名称已存在');
        }
        $admin_role->shop_id = $request->shop_id;
        $admin_role->save();
        admin_log('修改员工端角色 '.$request->role_name);
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
        $flight = Admin_role::find($id);
        $flight->delete();
        admin_log('删除了ID为 '.$id.' 的员工角色');
        return status(200, '删除成功');
    }
}
