<?php

namespace App\Http\Controllers\API\Admin;

use App\Models\Support;
use App\Models\SupportGroup;
use Illuminate\Http\Request;
use App\Rules\SupportRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\Admin\AbstractController;


class SupportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected function getModel()
    {
        return new Support();
    }
    
    public function index(Request $request)
    {
        $group=$request['group'];
        $query=Support::orderBy('id','desc');
        if($request->data == 'undefined' || $request->data =="")
        {
            $list = $query;
        }
        else{
            $list = $query->where("title", 'like', '%' . $request->data . '%');
        }
        if(isset($group)){
            $list=$query->where("group",$group);
        }
        $listSupport=$list->paginate(10);
        $SupportGroup = SupportGroup::get();
        return response()->json([
            'status' => true,
            'data' => $listSupport,
            'SupportGroup'=> $SupportGroup
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $list = parent::index();
        return response()->json([
            'status' => true,
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
            $support = new Support();
            $validator = SupportRequest::validate($request->all());
            if($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors(),
                ]); 
            }
            
            $support->	title = $request->title;
            $support->group =  $request->group;
            $support->email = $request->email;
            $support->phone = $request->phone;
            $support->name = $request->name;
            $support->type = $request->type;
            $support->menu_order = 0;
            $support->display = 1;
            $support->adminid = Auth::guard('admin')->user()->adminid;
            $support->lang = "vi";
            $support->save();
            return response()->json([
                'status' => true,
                'data' => $support,
            ]);
        }catch(\Throwable $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
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
        $supportId = Support::find($id);
        return response()->json([
            'status' => 'true',
            'data' => $supportId
        ]);

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
        try{
            $support = Support::Find($id);
            $validator = SupportRequest::validate($request->all());
            if($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors(),
                ]); 
            }
            $support->title = $request->title;
            $support->group =  $request->group;
            $support->email = $request->email;
            $support->phone = $request->phone;
            $support->name = $request->name;
            $support->type = $request->type;
            $support->menu_order = 0;
            $support->display = 1;
            $support->adminid = Auth::guard('admin')->user()->adminid;
            $support->lang = "vi";
            $support->save();
            return response()->json([
                'status' => true,
                'data' => $support,
            ]);
        }catch(\Throwable $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
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
        $list = Support::Find($id)->delete();
        return response()->json([
            'status' => true,
        ]);
    }
}