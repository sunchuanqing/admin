<?php

namespace App\Http\Controllers\Shop;

use App\Models\Shop;
use App\Models\Shop_type;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShopController extends Controller
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
            $list = Shop::with('shop_type')->where('shop_name', 'like', '%'.request('shop_name').'%')->orderBy('sort', 'desc')->orderBy('id', 'desc')->offset($num)->limit($limit)->get();
            $data = [
                'code' => 0,
                'msg' => 'ok',
                'count' => Shop::where('shop_name', 'like', '%'.request('shop_name').'%')->count(),
                'data' => $list
            ];
            return response()->json($data);
        }
        return view('shop.shop');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $shop_type = Shop_type::get();
        return view('shop.shop_add', ['shop_type' => $shop_type]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Shop::where('shop_name', $request->shop_name)->count() == 0){
            $shop = new Shop();
            $shop->shop_type_id = $request->shop_type_id;
            $shop->shop_name = $request->shop_name;
            $shop->shop_logo = $request->shop_logo;
            $shop->shop_video = $request->shop_video;
            $shop->shop_video_img = $request->shop_video_img;
            $shop->shop_thumb = $request->shop_img.'?imageView2/2/w/200/h/150/interlace/0/q/100';
            $shop->shop_img = $request->shop_img.'?imageView2/1/w/400/h/300/q/75|imageslim';
            $shop->original_img = $request->shop_img;
            if($request->start_time < $request->end_time){
                $shop->start_time = $request->start_time;
                $shop->end_time = $request->end_time;
            }else{
                return status(400, '时间输入不正确');
            }
            $shop->shop_admin = $request->shop_admin;
            $shop->shop_phone = $request->shop_phone;
            $shop->province = $request->province;
            $shop->city = $request->city;
            $shop->district = $request->district;
            $shop->street = $request->street;
            $shop->address = $request->address;
            $client = new \GuzzleHttp\Client();
            $ll = $client->request('GET', 'https://apis.map.qq.com/ws/geocoder/v1/?address='.$request->citytext.$request->add.$request->address.'&region='.$request->citytext.'&key=TO5BZ-WQ4L4-XEJUE-X2LST-HT2XZ-D4BMU');
            $ll_arr = json_decode($ll->getBody(), true);
            $shop->longitude = $ll_arr['result']['location']['lng'];
            $shop->latitude = $ll_arr['result']['location']['lat'];
            $shop->shop_status = $request->shop_status;
            $shop->sort = $request->sort;
            $shop->shop_brief = $request->shop_brief;
            $shop->save();
            admin_log('添加门店 '.$request->shop_name);
            return status(200, '添加成功');
        }else{
            return status(400, '门店名称已存在');
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
        $info = Shop::find($id);
        $shop_type = Shop_type::get();
        return view('shop.shop_update', ['data' => $info, 'shop_type' => $shop_type]);
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
        $shop = Shop::find($id);
        if(Shop::where('shop_name', $request->shop_name)->count() == 0){
            $shop->shop_name = $request->shop_name;
        }else if($shop->shop_name != $request->shop_name){
            return status(400, '门店名称已存在');
        }
        $shop->shop_type_id = $request->shop_type_id;
        $shop->shop_logo = $request->shop_logo;
        $shop->shop_video = $request->shop_video;
        $shop->shop_video_img = $request->shop_video_img;
        $shop->shop_thumb = $request->shop_img.'?imageView2/2/w/200/h/150/interlace/0/q/100';
        $shop->shop_img = $request->shop_img.'?imageView2/1/w/400/h/300/q/75|imageslim';
        $shop->original_img = $request->shop_img;
        if($request->start_time < $request->end_time){
            $shop->start_time = $request->start_time;
            $shop->end_time = $request->end_time;
        }else{
            return status(400, '时间输入不正确');
        }
        $shop->shop_admin = $request->shop_admin;
        $shop->shop_phone = $request->shop_phone;
        $shop->province = $request->province;
        $shop->city = $request->city;
        $shop->district = $request->district;
        $shop->street = $request->street;
        $shop->address = $request->address;
        $client = new \GuzzleHttp\Client();
        $ll = $client->request('GET', 'https://apis.map.qq.com/ws/geocoder/v1/?address='.$request->citytext.$request->add.$request->address.'&region='.$request->citytext.'&key=TO5BZ-WQ4L4-XEJUE-X2LST-HT2XZ-D4BMU');
        $ll_arr = json_decode($ll->getBody(), true);
        $shop->longitude = $ll_arr['result']['location']['lng'];
        $shop->latitude = $ll_arr['result']['location']['lat'];
        $shop->shop_status = $request->shop_status;
        $shop->sort = $request->sort;
        $shop->shop_brief = $request->shop_brief;
        $shop->save();
        admin_log('修改门店 '.$request->shop_name);
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
        $flight = Shop::find($id);
        $flight->delete();
        admin_log('删除了ID为 '.$id.' 的门店');
        return status(200, '删除成功');
    }

    // 修改门店状态
    public function shop_status (Request $request){
        $shop = Shop::find($request->id);
        if($shop->shop_status == 1){
            $shop->shop_status = 2;
            $msg = '休息中';
        }else{
            $shop->shop_status = 1;
            $msg = '营业中';
        }
        $shop->save();
        admin_log('设置门店ID为 '.$request->id.' 的营业状态为 '.$msg);
        return status(200, $msg);
    }
}
