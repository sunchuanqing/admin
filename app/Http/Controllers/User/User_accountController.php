<?php

namespace App\Http\Controllers\User;

use App\Models\User_account;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class User_accountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        if($request->money_change>0 && is_numeric($request->money_change)){
            $account = new User_account();
            $user = User::find($request->user_id);
            $moner = $request->money_change + $user->user_money;
            $account->money_change = $request->money_change;
            $account->money = $moner;
            $account->change_name = '后台充值';
            $account->change_desc = $request->change_desc;
            $account->user_id = $request->user_id;
            $account->account_sn = sn_20();
            $user->user_money = $moner;
            $data = $account;
            DB::beginTransaction();
            try {
                $account->save();
                $user->save();
                DB::commit();
                admin_log('给id为 '.$request->user_id.' 的会员充值 '.$request->money_change.' 元');
                return status(200, '充值成功', $data);
            } catch (QueryException $ex) {
                DB::rollback();
                return status(400, '充值失败');
            }
        }else{
            $account = new User_account();
            $user = User::find($request->user_id);
            $moner = $request->money_change + $user->user_money;
            $account->money_change = $request->money_change;
            $account->money = $moner;
            $account->change_name = '金额扣除';
            $account->change_desc = $request->change_desc;
            $account->user_id = $request->user_id;
            $account->account_sn = sn_20();
            $user->user_money = $moner;
            $data = $account;
            DB::beginTransaction();
            try {
                $account->save();
                $user->save();
                DB::commit();
                admin_log('给id为 '.$request->user_id.' 的会员充值 '.$request->money_change.' 元');
                return status(200, '充值成功', $data);
            } catch (QueryException $ex) {
                DB::rollback();
                return status(400, '充值失败');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if($request->ajax()){
            $page = $request->page;
            $limit = $request->limit;
            $num = ($page-1)*$limit;
            $list = User_account::where('user_id', $id)->offset($num)->limit($limit)->orderBy('id', 'desc')->get();
            $data = [
                'code' => 0,
                'msg' => 'ok',
                'count' => User_account::where('user_id', $id)->count(),
                'data' => $list
            ];
            return response()->json($data);
        }
        $user = User::find($id);
        return view('user_account.user_account', ['user' => $user]);
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
