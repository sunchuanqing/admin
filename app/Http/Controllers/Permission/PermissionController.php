<?php

namespace App\Http\Controllers\Permission;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
//        $permission = Permission::create(['name' => '查看会员账户', 'guard_name' => 'admin']);
//        dd($permission);
        if($request->ajax()){
            $page = request('page');
            $limit = request('limit');
            $num = ($page-1)*$limit;
            $list = Role::where('name', 'like', '%'.request('name').'%')->offset($num)->limit($limit)->get();
            $data = [
                'code' => 0,
                'msg' => 'ok',
                'count' => Role::where('name', 'like', '%'.request('name').'%')->count(),
                'data' => $list
            ];
            return response()->json($data);
        }
        return view('permission.permission');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('permission.permissionadd');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Role::where('name', request('name'))->first()){
            return status(410, '此角色已存在');
        }else{
            Role::create(['name' => $request->name, 'guard_name' => 'admin']);
            admin_log('添加角色：'.$request->name);
            return status(200, '添加成功');
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
        $info = Role::find($id);
        return view('permission.permissionupdate', ['data' => $info]);
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
        if($id == 1){
            return status(400, '超级管理员不许被修改');
        }else{
            $role = Role::find($id);
            $name = $role->name;
            if($name != $request->name){
                if(Role::where('name', $request->name)->first()){
                    return status(410, '此角色已存在');
                }else{
                    $role->name = $request->name;
                }
            }
            $role->save();
            admin_log('修改角色名 '.$name.' 为 '.$request->name);
            return status(200, '修改成功');
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
        if($id == 1){
            return status(400, '超级管理员不许被删除');
        }else{
            if(DB::table('model_has_roles')->where('role_id', '=', $id)->count()){
                return status(410, '此角色在使用不予删除');
            }else{
                $flight = Role::find($id);
                $flight->delete();
                admin_log('删除了名称为 '.$flight->name.' 的角色');
                return status(200, '删除成功');
            }
        }
    }


    // 加载分配权限列表
    public function permission_role (Request $request){
        if($request->ajax()){
            $page = request('page');
            $limit = request('limit');
            $num = ($page-1)*$limit;
            $list = Permission::where('name', 'like', '%'.request('name').'%')->offset($num)->limit($limit)->get();
            $info = DB::table('role_has_permissions')->where('role_id', '=', $request->id)->get();
            $infos = [];// 定义数组 把已有权限id存入
            foreach($info as $k=>$v){
                $infos[$k] = $v->permission_id;
            }
            foreach ($list as $k=>$v){
                if(in_array($v['id'], $infos)){// 判断是否具有此权限
                    $list[$k]['permission'] = 1;// 存在为1
                }else{
                    $list[$k]['permission'] = 0;// 不存在为0
                }
            }
            $data = [
                'code' => 0,
                'msg' => 'ok',
                'count' => Permission::where('name', 'like', '%'.request('name').'%')->count(),
                'data' => $list
            ];
            return response()->json($data);
        }else{
            return view('permission.permission_role', ['id' => $request->id]);
        }
    }

    // 执行分配权限接口
    public function permission_role_do (Request $request){
        $info = DB::table('role_has_permissions')->where('role_id', '=', $request->id)->where('permission_id', '=', $request->permission_id)->count();
        $role = Role::find($request->id);
        if($info){
            $permission_name = Permission::find($request->permission_id)->name;
            $role->revokePermissionTo($permission_name);
            admin_log('移除角色 '.$role->name.' 的 '.$permission_name.' 权限');
            return status(200, '移除权限成功', 0);
        }else{
            $permission_name = Permission::find($request->permission_id)->name;
            $role->givePermissionTo($permission_name);
            admin_log('添加角色 '.$role->name.' 的 '.$permission_name.' 权限');
            return status(200, '添加权限成功', 1);
        }
    }
}
