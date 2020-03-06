<?php

namespace App\Http\Controllers\Card;

use App\Models\Entity_gift_card;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class Entity_gift_cardController extends Controller
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
            $list = Entity_gift_card::where('user_gift_card_sn', 'like', '%'.request('user_gift_card_sn').'%')
                ->where('gift_card_id', $request->gift_card_id)
//                ->orderBy('id', 'desc')
                ->offset($num)
                ->limit($limit)
                ->get();
            $data = [
                'code' => 0,
                'msg' => 'ok',
                'count' => Entity_gift_card::where('user_gift_card_sn', 'like', '%'.request('user_gift_card_sn').'%')->where('gift_card_id', $request->gift_card_id)->count(),
                'data' => $list
            ];
            return response()->json($data);
        }
        return view('gift_card.entity_gift_card', ['gift_card_id' => $request->gift_card_id]);
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = [];
        for ($i=1; $i<=$request->number; $i++){
            array_push($data, ['gift_card_id' => $request->gift_card_id, 'user_gift_card_sn' => sn_19(), 'password' => mt_rand(100000, 999999), 'created_at' => date('Y-m-d H:i:s', time()), 'updated_at' => date('Y-m-d H:i:s', time())]);
        }
        DB::table('entity_gift_cards')->insert($data);
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
        //
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
        //
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
}
