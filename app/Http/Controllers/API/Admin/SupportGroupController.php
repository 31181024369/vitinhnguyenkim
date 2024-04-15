<?php

namespace App\Http\Controllers\API\Admin;

use App\Models\SupportGroup;
use Illuminate\Http\Request;
use App\Rules\SupportGroupRequest;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\Admin\AbstractController;

class SupportGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $keyword = $request->keyword;
        if($keyword)
        {
            $result = SupportGroup::where('title','LIKE',"%{$keyword}%")
                                    ->get();
            return response()->json($result);
        }
    }

    protected function getModel()
    {
        return new SupportGroup();
    }
    public function index(Request $request)
    {
        
        if($request->data == 'undefined' || $request->data =="")
        {
            $list = SupportGroup::get();
        }
        else{
            $list = SupportGroup::where("titleSupport", 'like', '%' . $request->data . '%')->get();
        }
        //$list = SupportGroup::get();
        return response()->json([
            'status' => true,
            'data' => $list
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $list = parent::create();
        return response()->json([
            'data' => $list         
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $list =new SupportGroup();
            $validator = SupportGroupRequest::validate($request->all());
            if($validator->fails()){
                return response()->json([
                    'status' => 'false',
                    'error' => $validator,
                ]);
            }
            $list->titleSupport = $request->titleSupport;
            $list->groupName = $request->groupName;
            $list->is_default = 0;
            $list->menu_order = 0;
            $list->save();

            return response()->json([
                'status' => 'true',
                'data' => $list
            ]);

        }catch(\Exception $e){
            return response()->json([
                'status' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
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
        try{
            $supportGroupId = SupportGroup::find($id);
            return response()->json([
                'status' => true,
                'data' => $supportGroupId
            ]);
        }catch(Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */ 
    public function update(Request $request,$id)
    {
        try{
            $data = $request->all();
            $supportId = SupportGroup::find($id);
            $supportId->titleSupport = $data['titleSupport'];
            $supportId->groupName = $data['groupName'];
            $supportId->save(); 
            return response()->json([
                'status' => true,
                'data' => $supportId
            ]);
        }catch(Exception $e){
            return response()->json([
                'status' => true,
                'error' => $e->getMessage(),
            ]);
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
        $supportId = SupportGroup::find($id)->delete();
        return response()->json([
            'status' => true
        ]);
    }
}
