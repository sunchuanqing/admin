<?php

namespace App\Http\Controllers\Card;

use App\Models\Entity_gift_card;
use App\Models\Gift_card;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Gift_CardController extends Controller
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
            $list = Gift_card::where('gift_card_name', 'like', '%'.request('gift_card_name').'%')
                ->orderBy('id', 'desc')
                ->offset($num)
                ->limit($limit)
                ->get();
            $data = [
                'code' => 0,
                'msg' => 'ok',
                'count' => Gift_card::where('gift_card_name', 'like', '%'.request('gift_card_name').'%')->count(),
                'data' => $list
            ];
            return response()->json($data);
        }
        return view('gift_card.gift_card');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('gift_card.gift_card_add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $gift_card = new Gift_card();
        $gift_card->gift_card_sn = sn_20();
        $gift_card->gift_card_name = $request->gift_card_name;
        $gift_card->gift_card_number = $request->gift_card_number;
        $gift_card->gift_card_type = $request->gift_card_type;
        $gift_card->gift_card_money = $request->gift_card_money;
        $gift_card->price = $request->price;
        $gift_card->vip_price = $request->vip_price;
        $gift_card->gift_card_brief = $request->gift_card_brief;
        $gift_card->gift_card_front_img = $request->gift_card_front_img;
        $gift_card->gift_card_reverse_img = $request->gift_card_reverse_img;
        $gift_card->gift_card_info_img = $request->gift_card_info_img;
        $gift_card->status = $request->status;
        $gift_card->gift_card_period = $request->gift_card_period;
        $gift_card->gift_card_notice = $request->gift_card_notice;
        $gift_card->save();
        admin_log('添加了礼品卡 '.$request->gift_card_name);
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
        $info = Gift_card::find($id);
        return view('gift_card.gift_card_update', ['data' => $info]);
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
        $gift_card = Gift_card::find($id);
        $gift_card->gift_card_name = $request->gift_card_name;
        $gift_card->gift_card_number = $request->gift_card_number;
        $gift_card->gift_card_type = $request->gift_card_type;
        $gift_card->gift_card_money = $request->gift_card_money;
        $gift_card->price = $request->price;
        $gift_card->vip_price = $request->vip_price;
        $gift_card->gift_card_brief = $request->gift_card_brief;
        $gift_card->gift_card_front_img = $request->gift_card_front_img;
        $gift_card->gift_card_reverse_img = $request->gift_card_reverse_img;
        $gift_card->gift_card_info_img = $request->gift_card_info_img;
        $gift_card->status = $request->status;
        $gift_card->gift_card_period = $request->gift_card_period;
        $gift_card->gift_card_notice = $request->gift_card_notice;
        $gift_card->save();
        admin_log('修改了礼品卡 '.$request->gift_card_name);
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
        //
    }


    /**
     * 实体礼品卡查看
     */
    public function entity_gift_card (Request $request){
        if($request->ajax()){
            $page = $request->page;
            $limit = $request->limit;
            $num = ($page-1)*$limit;
            $list = Entity_gift_card::where('user_gift_card_sn', 'like', '%'.request('user_gift_card_sn').'%')
                ->orderBy('id', 'desc')
                ->offset($num)
                ->limit($limit)
                ->get();
            $data = [
                'code' => 0,
                'msg' => 'ok',
                'count' => Entity_gift_card::where('user_gift_card_sn', 'like', '%'.request('user_gift_card_sn').'%')->count(),
                'data' => $list
            ];
            return response()->json($data);
        }
        return view('gift_card.entity_gift_card');
    }
}
