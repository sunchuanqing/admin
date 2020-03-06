<?php

namespace App\Http\Controllers\Version;

use App\Models\App_version;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VersionController extends Controller
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
            $list = App_version::where('version_number', 'like', '%'.request('version_number').'%')->offset($num)->limit($limit)->OrderBy('id', 'desc')->get();
            $data = [
                'code' => 0,
                'msg' => 'ok',
                'count' => App_version::where('version_number', 'like', '%'.request('version_number').'%')->count(),
                'data' => $list
            ];
            return response()->json($data);
        }
        return view('version.version');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('version.version_add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $version = new App_version();
        $version->version_number = $request->version_number;
        $version->version_url = $request->version_url;
        $version->version_info = $request->version_info;
        $version->save();
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
