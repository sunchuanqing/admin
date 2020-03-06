<?php

namespace App\Http\Controllers\Card;

use App\Models\Card;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CardController extends Controller
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
            $list = Card::where('card_name', 'like', '%'.request('card_name').'%')
                ->orderBy('id', 'desc')
                ->offset($num)
                ->limit($limit)
                ->get();
            $data = [
                'code' => 0,
                'msg' => 'ok',
                'count' => Card::where('card_name', 'like', '%'.request('card_name').'%')->count(),
                'data' => $list
            ];
            return response()->json($data);
        }
        return view('card.card');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('card.card_add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $card = new Card();
        $card->card_sn = sn_20();
        $card->card_name = $request->card_name;
        $card->card_number = $request->card_number;
        $card->price = $request->price;
        $card->vip_price = $request->vip_price;
        $card->card_img = $request->card_img;
        $card->card_whole_img = $request->card_whole_img;
        $card->status = $request->status;
        $card->card_brief = $request->card_brief;
        $card->card_period = $request->card_period;
        $card->phone = $request->phone;
        $card->card_notice = $request->card_notice;
        $card->shop_id = $request->shop_id;
        $card->save();
        admin_log('添加了卡片 '.$request->card_name);
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
        $info = Card::find($id);
        return view('card.card_update', ['data' => $info]);
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
        $card = Card::find($id);
        $card->card_name = $request->card_name;
        $card->card_number = $request->card_number;
        $card->price = $request->price;
        $card->vip_price = $request->vip_price;
        $card->card_img = $request->card_img;
        $card->card_whole_img = $request->card_whole_img;
        $card->status = $request->status;
        $card->card_brief = $request->card_brief;
        $card->card_period = $request->card_period;
        $card->phone = $request->phone;
        $card->card_notice = $request->card_notice;
        $card->shop_id = $request->shop_id;
        $card->save();
        admin_log('修改了卡片 '.$request->card_name);
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
        $flight = Card::find($id);
        $flight->delete();
        admin_log('删除了ID为 '.$id.' 的卡片');
        return status(200, '删除成功');
    }
}
