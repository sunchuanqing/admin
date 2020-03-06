<?php

namespace App\Http\Controllers\Login;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // 后台管理员登录
    public function login (){
        return view('login.login');
    }

    // 后台登录判断登录方式(手机号 邮箱 用户名)
    public function verify (Request $request){
        if(preg_match("/^1\d{10}$/", $request->name)){
            return $this ->adminlogin('phone', $request->name, $request->password);
        }else if(preg_match("/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/", $request->name)){
            return $this ->adminlogin('email', $request->name, $request->password);
        }else{
            return $this ->adminlogin('name', $request->name, $request->password);
        }
    }
    // 判断管理员登录密码是否正确
    public function adminlogin ($type, $value, $password){
        if(Admin::where($type, $value)->count() == 1){
            $admin = Admin::where($type, $value)->first();
            if($admin->status == 1){
                $str = md5($password);
                if(strcmp($admin->password,$str) === 0){
                    $admin->last_ip = $_SERVER["REMOTE_ADDR"];
                    $admin->last_time = date('Y-m-d H:i:s', time());
                    $admin->save();
                    Auth::guard('admin')->login($admin);
                    admin_log('登录成功');
                    return status('200', '登录成功');
                }else{
                    return status('400', '密码输入有误');
                }
            }else{
                return status('400', '此管理员已被禁用');
            }
        }else{
            return status('404', '此账号不存在');
        }
    }

    // 后台管理员退出登录
    public function loginout (){
        admin_log('退出登录');
        Auth::guard('admin')->logout();
        return redirect('login');
    }
}