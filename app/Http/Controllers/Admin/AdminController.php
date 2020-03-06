<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\Admin_account;
use App\Models\Admin_log;
use App\Models\Admin_role;
use App\Models\Shop;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
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
            $list = Admin::where('name', 'like', '%'.request('name').'%')
                ->where('phone', 'like', '%'.request('phone').'%')
                ->join('admin_roles', 'admins.admin_role_id', '=', 'admin_roles.id')
                ->offset($num)
                ->limit($limit)
                ->select(['admins.id', 'admins.name', 'admins.phone', 'admins.role_name', 'admins.status', 'admins.last_time', 'admins.last_ip', 'admin_roles.role_name as rolename'])
                ->get();
            $data = [
                'code' => 0,
                'msg' => 'ok',
                'count' => Admin::where('name', 'like', '%'.request('name').'%')->where('phone', 'like', '%'.request('phone').'%')->count(),
                'data' => $list
            ];
            return response()->json($data);
        }
        return view('admin.admin');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $role = Role::where('id', '>', 1)->get();
        $shop = Shop::get();
        return view('admin.adminadd', ['role' => $role, 'shop' => $shop]);
    }


    public function role_list (Request $request){
        $role_list = Admin_role::where('shop_id', $request->shop_id)->get();
        $str = "<option value=''></option>";
        foreach ($role_list as $k => $v){
            $str = $str."<option value='".$v['id']."'>".$v['role_name']."</option>";
        }
        return status(200, $str);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Admin::orWhere('phone', $request->phone)->orWhere('name', $request->name)->count() >= 1){
            return status(410, '此管理员已存在');
        }else if($request->password === $request->qrpassword){
            DB::beginTransaction();
            try {
                $role = Role::find($request->role);
                $adminadd = new Admin();
                $adminadd->name = $request->name;
                $adminadd->phone = $request->phone;
                $adminadd->sex = $request->sex;
                $adminadd->photo = $request->photo;
                $adminadd->todolist = $request->todolist;
                $adminadd->role_id = $request->role;
                $adminadd->role_name = $role->name;
                $adminadd->password = MD5($request->password);
                $adminadd->status = $request->status;
                $adminadd->last_time = date('Y-m-d H:i:s', time());
                $adminadd->last_ip = $_SERVER["REMOTE_ADDR"];
                if($request->admin_status == 1){
                    if(empty($request->admin_role)) return status(400, '员工角色不能为空');
                    $adminadd->admin_status = 1;
                    $adminadd->admin_role_id = $request->admin_role;
                }else if($request->admin_status == 2){
                    $adminadd->admin_status = 2;
                    $adminadd->admin_role_id = 2;// 数据库里的暂无  表admin_role中
                }else{
                    $adminadd->admin_status = 1;
                    $adminadd->admin_role_id = 1;// 数据库里的管理员  表admin_role中
                }
                $adminadd->save();
                $adminadd->assignRole($role->name);
                DB::commit();
                admin_log('添加管理员：'.$request->name);
                return status(200, '添加成功');
            } catch (QueryException $ex) {
                DB::rollback();
                return status(400, '添加失败');
            }
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
        $info = Admin::join('admin_roles', 'admins.admin_role_id', '=', 'admin_roles.id')
            ->select(['admins.id', 'admins.name', 'admins.phone', 'admins.email', 'admins.sex', 'admins.photo', 'admins.todolist', 'admins.role_id', 'admins.role_name', 'admins.status', 'admins.admin_status', 'admins.admin_role_id', 'admins.last_time', 'admins.last_ip', 'admin_roles.shop_id'])
            ->find($id);
        $role = Role::where('id', '>', 1)->get();
        $shop = Shop::get();
        if($info['admin_role_id'] == 1){
            $info['admin_status'] = 3;
        }
        if($info['shop_id'] == 0){
            return view('admin.adminupdate', ['data' => $info, 'role' => $role, 'shop' => $shop]);
        }else{
            $admin_role = Admin_role::where('shop_id', $info['shop_id'])->get();
            return view('admin.adminupdate', ['data' => $info, 'role' => $role, 'shop' => $shop, 'admin_role' => $admin_role]);
        }
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
            DB::beginTransaction();
            try {
                $role = Role::find($request->role);
                $admin = Admin::find($id);
                if($admin->role_id != $request->role){
                    $admin->removeRole($admin->role_name);
                    $admin->assignRole($role->name);
                }
                if($admin->name != $request->name){
                    if(Admin::where('name', $request->name)->count() == 0){
                        $admin->name = $request->name;
                    }else{
                        return status(400, '用户名已存在');
                    }
                }
                if($admin->phone != $request->phone){
                    if(Admin::where('phone', $request->phone)->count() == 0){
                        $admin->phone = $request->phone;
                    }else{
                        return status(400, '手机号已存在');
                    }
                }
                $admin->sex = $request->sex;
                $admin->photo = $request->photo;
                $admin->todolist = $request->todolist;
                $admin->role_id = $request->role;
                $admin->role_name = $role->name;
                $admin->status = $request->status;
                if($request->admin_status == 1){
                    if(empty($request->admin_role)) return status(400, '员工角色不能为空');
                    $admin->admin_status = 1;
                    $admin->admin_role_id = $request->admin_role;
                }else if($request->admin_status == 2){
                    $admin->admin_status = 2;
                    $admin->admin_role_id = 2;// 数据库里的暂无  表admin_role中
                }else{
                    $admin->admin_status = 1;
                    $admin->admin_role_id = 1;// 数据库里的管理员  表admin_role中
                }
                $admin->save();
                DB::commit();
                admin_log('修改了ID为 '.$admin->id.' 的管理员信息');
                return status(200, '修改成功');
            } catch (QueryException $ex) {
                DB::rollback();
                return status(400, '修改失败');
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
        if($id == 1){
            return status(400, '超级管理员不许被删除');
        }else{
            $flight = Admin::find($id);
            $flight->delete();
            admin_log('删除了ID为 '.$id.' 的管理员');
            return status(200, '删除成功');
        }
    }

    // 修改管理员状态
    public function admin_status (Request $request){
        if($request->id == 1){
            return status(400, '超级管理员不许被修改');
        }else{
            $admin = Admin::find($request->id);
            if($admin->status == 1){
                $admin->status = 2;
                $msg = '禁用';
            }else{
                $admin->status = 1;
                $msg = '启用';
            }
            $admin->save();
            admin_log($msg.'了id为 '.$request->id.' 管理员');
            return status(200, $msg);
        }
    }

    // 查看管理员日志
    public function admin_log (Request $request){
        if($request->ajax()){
            $page = $request->page;
            $limit = $request->limit;
            $num = ($page-1)*$limit;
            if(request('id')){
                $list = Admin_log::where('admin_id', request('id'))->where('admin_info', 'like', '%'.request('admin_info').'%')->where('log_info', 'like', '%'.request('log_info').'%')->offset($num)->limit($limit)->orderBy('id', 'desc')->get();
                $num = Admin_log::where('admin_id', request('id'))->where('admin_info', 'like', '%'.request('admin_info').'%')->where('log_info', 'like', '%'.request('log_info').'%')->count();
            }else{
                $list = Admin_log::where('admin_info', 'like', '%'.request('admin_info').'%')->where('log_info', 'like', '%'.request('log_info').'%')->offset($num)->limit($limit)->orderBy('id', 'desc')->get();
                $num = Admin_log::where('admin_info', 'like', '%'.request('admin_info').'%')->where('log_info', 'like', '%'.request('log_info').'%')->count();
            }
            $data = [
                'code' => 0,
                'msg' => 'ok',
                'count' => $num,
                'data' => $list
            ];
            return response()->json($data);
        }
        if(request('id')){
            return view('admin.admin_log', ['admin_id' => request('id')]);
        }else{
            return view('admin.admin_log', ['admin_id' => 0]);
        }
    }

    // 修改管理员密码
    public function admin_pass (Request $request){
        if($request->ajax()){
            if($request->id == 1){
                return status('400', '超级管理员不许被修改');
            }else{
                if($request->password == $request->qrpassword){
                    $admin = Admin::find($request->id);
                    $admin->password = md5($request->password);
                    $admin->save();
                    admin_log('修改了id为 '.$request->id.' 的管理员密码');
                    return status('200', '修改成功');
                }else{
                    return status('400', '两次密码输入不一致');
                }
            }
        }
        return view('admin.password', ['id' => $request->id]);
    }

    // 管理员回收站
    public function admin_recycle (Request $request){
        if($request->ajax()){
            $page = $request->page;
            $limit = $request->limit;
            $num = ($page-1)*$limit;
            $list = Admin::onlyTrashed()->where('name', 'like', '%'.request('name').'%')->where('phone', 'like', '%'.request('phone').'%')->offset($num)->limit($limit)->get();
            $data = [
                'code' => 0,
                'msg' => 'ok',
                'count' => Admin::onlyTrashed()->where('name', 'like', '%'.request('name').'%')->where('phone', 'like', '%'.request('phone').'%')->count(),
                'data' => $list
            ];
            return response()->json($data);
        }
        return view('admin.admin_recycle');
    }

    // 永久删除管理员
    public function admin_del (Request $request){
        $admin = Admin::onlyTrashed()->find($request->id);
        $admin->forceDelete();
        admin_log('永久删除id为 '.$request->id.' 的管理员');
        return status('200', '删除成功');
    }

    // 回复管理员删除
    public function admin_recover (Request $request){
        $admin = Admin::onlyTrashed()->find($request->id);
        $admin->restore();
        admin_log('恢复了id为 '.$request->id.' 的管理员');
        return status('200', '恢复成功');
    }


    // 管理员流水
    public function admin_account (Request $request){
        if($request->ajax()){
            $page = $request->page;
            $limit = $request->limit;
            $num = ($page-1)*$limit;
            $list = Admin_account::where('admin_id', $request->id)
                ->where('account_sn', 'like', '%'.request('account_sn').'%')
                ->offset($num)
                ->limit($limit)
                ->orderBy('id', 'desc')
                ->get();
            $data = [
                'code' => 0,
                'msg' => 'ok',
                'count' => Admin_account::where('admin_id', $request->id)->where('account_sn', 'like', '%'.request('account_sn').'%')->count(),
                'data' => $list
            ];
            return response()->json($data);
        }
        return view('admin.admin_account', ['id' => $request->id]);
    }
}
