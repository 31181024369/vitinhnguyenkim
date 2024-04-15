<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;
use Validator;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $permission=Permission::get();
        return response()->json([
            'status'=>true,
            'data'=>$permission
        ]);

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
        //
        try{
            $data = $request->all();
            $validator = Validator::make($request->all(),[
                'name' => 'required',
                'slug' => 'required',
            ]);
            if($validator->fails()){
                return response()->json([
                    'message'=>'Validations fails',
                    'errors'=>$validator->errors()
                ],422);
            }
            $permission=new Permission();
            $permission->name=$data['name'];
            $permission->slug=$data['slug'];
            $permission->guard_name="web";
            $permission->save();
            return response()->json([
                'status'=>true,
                'data'=>$permission
            ]);
        }catch(\Throwable $th){
            return response()->json([
              'status' => false,
              'message' => $th->getMessage()
            ]);
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
        $permission=Permission::where('id',$id)->first();
        return response()->json([
            'status'=>true,
            'data'=>$permission
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $permission=Permission::where('id',$id)->first();
        return response()->json([
            'status'=>true,
            'data'=>$permission
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
            $data = $request->all();
            $validator = Validator::make($request->all(),[
                'name' => 'required',
                'slug' => 'required',
            ]);
            if($validator->fails()){
                return response()->json([
                    'message'=>'Validations fails',
                    'errors'=>$validator->errors()
                ],422);
            }
            $permission=Permission::where('id', $id)->first();
            $permission->name=$data['name'];
            $permission->slug=$data['slug'];
            $permission->guard_name="web";
            $permission->save();
            return response()->json([
                'status'=>true,
                'data'=>$permission
            ]);
        }catch(\Throwable $th){
            return response()->json([
              'status' => false,
              'message' => $th->getMessage()
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
        $permission=Permission::where('id', $id)->first();
        $permission->delete();
        return response()->json([
            'status' => true
        ]);
        

    }
}
