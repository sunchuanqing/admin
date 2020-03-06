<?php

namespace App\Http\Controllers\Coupon;

use App\Models\Coupon;
use App\Models\Coupon_good;
use App\Models\Coupon_type;
use App\Models\Coupon_user;
use App\Models\Flower;
use App\Models\Good;
use App\Models\Shop;
use App\Models\Shop_serve;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CouponController extends Controller
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
            $list = Coupon::with('coupon_types')->where('name', 'like', '%'.request('name').'%')->offset($num)->limit($limit)->get();
            $data = [
                'code' => 0,
                'msg' => 'ok',
                'count' => Coupon::where('name', 'like', '%'.request('name').'%')->count(),
                'data' => $list
            ];
            return response()->json($data);
        }
        return view('coupon.coupon');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('coupon.coupon_add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->coupon_type == 1){
            $coupon_type_name = '现金券';
        }else if($request->coupon_type == 2){
            $coupon_type_name = '满减券';
        }else if($request->coupon_type == 3){
            $coupon_type_name = '新人券';
        }else if($request->coupon_type == 4){
            $coupon_type_name = '积分兑换券';
        }else{
            return status(400, '类别有误');
        }
        DB::beginTransaction();
        try {
            $coupon = new Coupon();
            $coupon->coupon_sn = sn_20();
            $coupon->name = $request->name;
            $coupon->number = $request->number;
            $coupon->user_number = $request->user_number;
            $coupon->img = $request->img;
            $coupon->start_time = $request->start_time;
            $coupon->end_time = $request->end_time;
            $coupon->valid_type = $request->valid_type;
            $coupon->valid_start_time = $request->valid_start_time;
            $coupon->valid_end_time = $request->valid_end_time;
            $coupon->valid_day = $request->valid_day;
            if($request->pay_money > 0){
                $coupon->pay_type = 2;
                $coupon->pay_money = $request->pay_money;
            }else if($request->pay_money == 0){
                $coupon->pay_type = 1;
            }else{
                return status(400, '付费金额不合法');
            }
            $coupon->coupon_type = $request->coupon_type;
            $coupon->subject_type = $request->subject_type;
            $coupon->grant_type = $request->grant_type;
            $coupon->usable_range = $request->usable_range;
            $coupon->else_msg = $request->else_msg;
            $coupon->bc_msg = $request->bc_msg;
            $coupon_type = new Coupon_type();
            $coupon_type->coupon_type = $request->coupon_type;
            $coupon_type->coupon_type_name = $coupon_type_name;
            $coupon_type->money = $request->money;
            $coupon_type->full_money = $request->full_money;
            $coupon->save();
            $coupon_type->save();
            DB::commit();
            admin_log('添加优惠券：'.$request->name);
            return status(200, '添加成功');
        } catch (QueryException $ex) {
            DB::rollback();
            return status(400, '添加失败');
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
        $info = Coupon::with('coupon_types')->find($id);
        return view('coupon.coupon_update', ['data' => $info]);
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
        if($request->coupon_type == 1){
            $coupon_type_name = '现金券';
        }else if($request->coupon_type == 2){
            $coupon_type_name = '满减券';
        }else if($request->coupon_type == 3){
            $coupon_type_name = '新人券';
        }else if($request->coupon_type == 4){
            $coupon_type_name = '积分兑换券';
        }else{
            return status(400, '类别有误');
        }
        DB::beginTransaction();
        try {
            $coupon = Coupon::find($id);;
            $coupon->name = $request->name;
            $coupon->number = $request->number;
            $coupon->user_number = $request->user_number;
            $coupon->img = $request->img;
            $coupon->start_time = $request->start_time;
            $coupon->end_time = $request->end_time;
            $coupon->valid_type = $request->valid_type;
            $coupon->valid_start_time = $request->valid_start_time;
            $coupon->valid_end_time = $request->valid_end_time;
            $coupon->valid_day = $request->valid_day;
            if($request->pay_money > 0){
                $coupon->pay_type = 2;
                $coupon->pay_money = $request->pay_money;
            }else if($request->pay_money == 0){
                $coupon->pay_type = 1;
            }else{
                return status(400, '付费金额不合法');
            }
            $coupon->coupon_type = $request->coupon_type;
            if($coupon->subject_type != $request->subject_type){
                $coupon->subject_type = $request->subject_type;
                $coupon->shop_id = 0;
                $coupon->status = 1;
            }
            $coupon->grant_type = $request->grant_type;
            $coupon->usable_range = $request->usable_range;
            $coupon->else_msg = $request->else_msg;
            $coupon->bc_msg = $request->bc_msg;
            Coupon_type::where('coupon_id', $id)->update(['coupon_type' => $request->coupon_type, 'coupon_type_name' => $coupon_type_name, 'money' => $request->money, 'full_money' => $request->full_money]);
            $coupon->save();
            DB::commit();
            admin_log('修改了ID为 '.$id.' 的优惠券');
            return status(200, '修改成功');
        } catch (QueryException $ex) {
            DB::rollback();
            return status(400, '修改失败');
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
        $flight = Coupon::find($id);
        $flight->delete();
        admin_log('删除了ID为 '.$id.' 的优惠券');
        return status(200, '删除成功');
    }

    // 检测优惠券是否需要绑定门店 或者指定商品
    public function coupon_examine (Request $request){
        $info = Coupon::find($request->id);
        if($info->status == 1){
            switch ($request->subject_type)
            {
                case 2:// 门店
                    if($info->shop_id > 0){
                        $info->status = 2;// 2为检测通过 正常使用中
                        $info->save();
                        admin_log('检测通过了优惠券ID '.$request->id);
                        return status(200, '检测通过');
                    }else{
                        return status(400, '缺少绑定门店', $info);
                    }
                    break;
                case 4:// 商品
                    if(Coupon_good::where('coupon_id', $request->id)->count() > 0){
                        $info->status = 2;// 2为检测通过 正常使用中
                        $info->save();
                        admin_log('检测通过了优惠券ID '.$request->id);
                        return status(200, '检测通过');
                    }else{
                        return status(400, '缺少绑定商品', $info);
                    }
                    break;
                default:
                    $info->status = 2;// 2为检测通过 正常使用中
                    $info->save();
                    admin_log('检测通过了优惠券ID '.$request->id);
                    return status(200, '检测通过');
            }
        }else if($info->status == 2){
            return status(200, '已通过检测');
        }else if($info->status == 3){
            return status(400, '已停用');
        }
    }

    // 判断优惠券绑定的是门店还是单一商品
    public function coupon_subject (Request $request){
        $info = Coupon::find($request->id);
        switch ($info->subject_type)
        {
            case 2://门店
                $shop = Shop::get();
                return view('coupon.coupon_shop_add', ['shop' => $shop, 'id' => $info->id]);
                break;
            case 4://商品
                if($request->ajax()){
                    $page = $request->page;
                    $limit = $request->limit;
                    $num = ($page-1)*$limit;
                    $list = Coupon_good::where('coupon_id', $request->id)->where('goods_sn', 'like', '%'.request('goods_sn').'%')->where('goods_name', 'like', '%'.request('goods_name').'%')->offset($num)->limit($limit)->get();
                    $data = [
                        'code' => 0,
                        'msg' => 'ok',
                        'count' => Coupon_good::where('coupon_id', $request->id)->where('goods_sn', 'like', '%'.request('goods_sn').'%')->where('goods_name', 'like', '%'.request('goods_name').'%')->count(),
                        'data' => $list
                    ];
                    return response()->json($data);
                }
                return view('coupon.coupon_goods', ['id' => $info->id]);
                break;
            default:
                return view('coupon.coupon');
                break;
        }
    }

    // 给优惠券绑定主体 门店
    public function coupon_shop (Request $request){
        $coupon = Coupon::find($request->id);
        $coupon->shop_id = $request->shop_id;
        $coupon->save();
        admin_log('给优惠券ID为 '.$request->id.' 绑定了门店');
        return status(200, '绑定成功');
    }

    // 给优惠券绑定主体 商品
    public function coupon_goods (Request $request){
        if($request->ajax()){
            if(Coupon_good::where('goods_sn', $request->goods_sn)->where('coupon_id', $request->id)->count() == 1){
                return status(400, '此商品已添加');
            }else{
                $goods = Good::where('goods_sn', $request->goods_sn)->count();
                $shop_serve = Shop_serve::where('serve_sn', $request->goods_sn)->count();
                $flower = Flower::where('flower_sn', $request->goods_sn)->count();
                if($goods == 1){
                    $data = Good::where('goods_sn', $request->goods_sn)->first();
                    $info = new Coupon_good();
                    $info->coupon_id = $request->id;
                    $info->goods_sn = $request->goods_sn;
                    $info->goods_name = $data->goods_name;
                    $info->save();
                    admin_log('给优惠券ID为 '.$request->id.' 添加了商品 '.$request->goods_sn);
                    return status(200, '添加成功');
                }else if($shop_serve == 1){
                    $data = Shop_serve::where('serve_sn', $request->goods_sn)->first();
                    $info = new Coupon_good();
                    $info->coupon_id = $request->id;
                    $info->goods_sn = $request->goods_sn;
                    $info->goods_name = $data->serve_name;
                    $info->save();
                    admin_log('给优惠券ID为 '.$request->id.' 添加了商品 '.$request->goods_sn);
                    return status(200, '添加成功');
                }else if($flower == 1){
                    $data = Flower::where('flower_sn', $request->goods_sn)->first();
                    $info = new Coupon_good();
                    $info->coupon_id = $request->id;
                    $info->goods_sn = $request->goods_sn;
                    $info->goods_name = $data->flower_name;
                    $info->save();
                    admin_log('给优惠券ID为 '.$request->id.' 添加了商品 '.$request->goods_sn);
                    return status(200, '添加成功');
                }else{
                    return status(400, '商品不存在');
                }
            }
        }
        return view('coupon.coupon_goods_add', ['id' => $request->id]);
    }

    // 优惠券后台发放给用户
    public function coupon_user (Request $request){
        $coupon = Coupon::with('coupon_types')->find($request->id);
        $count = Coupon_user::where('coupon_id', $request->id)->count();
        if($coupon->status == 1) return status(403, '未检测');
        if($coupon->status == 3) return status(403, '已禁用');
        if($coupon->start_time >= date('Y-m-d H:i:s', time())) return status(403, '还未开始');
        if($coupon->end_time <= date('Y-m-d H:i:s', time())) return status(403, '已经结束');
        if($coupon->pay_type == 2) return status(403, '付费优惠券禁止发放');
        if($count < $coupon->number){
            if($coupon->valid_type == 1){
                $coupon_start_time = $coupon->valid_start_time;
                $coupon_end_time = $coupon->valid_end_time;
            }else{
                $coupon_start_time = date('Y-m-d', time());
                $coupon_end_time = date('Y-m-d', time()+86400*$coupon->valid_day);
            }
            $user = User::where('flag', 1)->get(['id']);
            $number = 0;
            foreach ($user as $k => $v){
                if(Coupon_user::where('coupon_id', $request->id)->where('user_id', $v->id)->count() < $coupon->user_number){
                    $info['user_id'] = $v->id;
                    $info['coupon_id'] = $request->id;
                    $info['coupon_sn'] = sn_26();
                    $info['money'] = $coupon['coupon_types']['money'];
                    $info['full_money'] = $coupon['coupon_types']['full_money'];
                    $info['discount'] = $coupon['coupon_types']['discount'];
                    $info['coupon_name'] = $coupon->name;
                    $info['coupon_img'] = $coupon->img;
                    $info['coupon_start_time'] = $coupon_start_time;
                    $info['coupon_end_time'] = $coupon_end_time;
                    $info['coupon_type'] = $coupon->coupon_type;
                    $info['coupon_type_name'] = $coupon['coupon_types']['coupon_type_name'];
                    $info['status'] = 1;
                    $info['subject_type'] = $coupon->subject_type;
                    $info['shop_id'] = $coupon->shop_id;
                    $info['usable_range'] = $coupon->usable_range;
                    $info['else_msg'] = $coupon->else_msg;
                    $info['bc_msg'] = $coupon->bc_msg;
                    Coupon_user::create($info);
                    $number++;
                    $count++;
                    if($count >= $coupon->number){
                        $coupon->number = $coupon->number-$number;
                        $coupon->save();
                        return status('200', '本次总计发出'.$number.'张,已达到最大数量', $coupon->number);
                    }
                }
            }
            $coupon->number = $coupon->number-$number;
            $coupon->save();
            return status('200', '本次总计发出'.$number.'张', $coupon->number);
        }else{
            return status('400', '优惠券已发完');
        }
    }
}
