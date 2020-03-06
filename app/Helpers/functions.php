<?php
//返回操作状态
function status (int $code, $msg, $data = ''){
    $info['code'] = $code;
    $info['msg'] = $msg;
    if($data)
        $info['data'] = $data;
    return response()->json($info);
}

//操作日志
function admin_log ($log_info){
    $admin = \Illuminate\Support\Facades\Auth::guard('admin')->user();
    $admin_log = new \App\Models\Admin_log();
    $admin_log->admin_id = $admin->id;
    $admin_log->admin_info = $admin->name.'：'.$admin->phone;
    $admin_log->log_info = $log_info;
    $admin_log->ip_address = $_SERVER["REMOTE_ADDR"];
    $admin_log->save();
}

// 返回错误码
function error (int $code){
    switch ($code) {
        case '400':
            $msg='Bad Request';// 请求参数有误，语义有误，当前请求无法被服务器理解
            break;
        case '401':
            $msg='Unauthorized';// 没有授权 需要登录
            break;
        case '403':
            $msg='Access Denied';
            break;
        case '404':
            $msg='Not Found';// 找不到可用数据
            break;
        case '405':
            $msg='Method Not Allowed';
            break;
        case '406':
            $msg='Not Acceptable';
            break;
        case '409':
            $msg='Conflict';
            break;
        case '410':
            $msg='Gone';
            break;
        case '411':
            $msg='Length Required';
            break;
        case '412':
            $msg='Precondition Failed';
            break;
        case '415':
            $msg='Unsupported Media Type';
            break;
        case '428':
            $msg='Precondition Required';
            break;
        case '429':
            $msg='TooMany Requests';
            break;
        case '500':
            $msg='Http Exception';
            break;
        case '503':
            $msg='Service Unavailable';
            break;
        default:
            $msg='Http Exception';
            break;
    }
    $info['code'] = $code;
    $info['msg'] = $msg;
    return response()->json($info);
}

//生成26位单号
function sn_26 (){
    $sn = date('YmdHis', time()).substr(microtime(), 2, 6).mt_rand(100, 999).mt_rand(100, 999);
    return $sn;
}

//生成20位单号
function sn_20 (){
    $sn = date('YmdHis', time()).substr(microtime(), 2, 3).mt_rand(100, 999);
    return $sn;
}

// 礼品卡卡号 19位
function sn_19 (){
    $sn = substr(date('YmdHis', time()), 2, 12).substr(microtime(), 2, 6).mt_rand(0, 9);
    return $sn;
}


function order_sn (){
    $sn = substr(date('YmdHis', time()), 2, 10).mt_rand(10000, 99999);
    if(\App\Models\Order::where('order_sn', $sn)->count() == 0){
        return $sn;
    }else{
        order_sn();
    }
}