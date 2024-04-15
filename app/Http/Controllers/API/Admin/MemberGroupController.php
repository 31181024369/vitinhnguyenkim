<?php

namespace App\Http\Controllers\API\Admin;

use App\Models\MemGroup;
use Illuminate\Http\Request;
use App\Rules\MemberGroupRequest;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\Admin\AbstractController;

class MemberGroupController extends AbstractController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected function getModel()
    {
        return new MemGroup();
    }
    public function index()
    {
        try{
            $list = parent::index();
            return response()->json([
                'list' => $list,
                'status' => true
            ]);
        }catch(\Throwable $th){
            return response()->json([
                'status' => true,
                'message' => $th->getMessage(),
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try{
            $list = parent::create();
            return response()->json([
                'list' => $list,
                'status' => true
            ]);
        }catch(\Throwable $th){
            return response()->json([
                'status' => true,
                'message' => $th->getMessage(),
            ]);
        }
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
            $data = $request->all();
            $validate = MemberGroupRequest::validate($request->all());
            if($validator->fails()){
                return response()->json([
                    'message'=>'Validations fails',
                    'errors'=>$validator->errors()
                ],422);
            }
            $list = parent::store($data);
            return response()->json([
                'status'=> true,
                'list'=>$list
            ]); 
        }catch(\Throwable $th){
            return response()->json([
                'status'=>false
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
