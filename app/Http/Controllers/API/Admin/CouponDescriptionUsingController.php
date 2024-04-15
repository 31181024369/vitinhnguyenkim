<?php

namespace App\Http\Controllers\API\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\CouponDesUsing;
use App\Http\Controllers\Controller;
use App\Rules\CouponDesUsingRequest;

class CouponDescriptionUsingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   
    public function index()
    {
        $listCouponDescriptionUsing = CouponDesUsing::all();
        return response()->json([
            'listCouponDescriptionUsing'=>$listCouponDescriptionUsing,
            'status' => true
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $listCouponDescriptionUsing = CouponDesUsing::all();
        $lstCouponDesc = CouponDes::all();
        return response()->json([
            'listCouponDescriptionUsing'=>$listCouponDescriptionUsing,
            'status' => true,
            'lstCouponDesc' => $lstCouponDesc
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
            $validator = CouponDesUsingRequest::validate($request->all());
            if($validator->fails()){
                return response()->json([
                    'message'=>'Validations fails',
                    'errors'=>$validator->errors()
                ],422);
            }
            $data = $request->all();
            $date = Carbon::now('Asia/Ho_Chi_Minh')->isoFormat('DD-MM-YYYY');
            $CouponDesId = new CouponDesUsing();
            $CouponDesId -> IDuser = $data['IDuser'];
            $CouponDesId -> idCouponDes = $data['idCouponDes'];
            $CouponDesId -> DateUsingCode = $data['DateUsingCode'];
            $CouponDesId -> IDOrderCode = $data['IDOrderCode'];
            $CouponDesId -> MaCouponUSer = $data['MaCouponUSer'];
            $CouponDesId -> save();
            return response()->json(['CouponDesId'=>$CouponDesId,'status'=>true]);  
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
