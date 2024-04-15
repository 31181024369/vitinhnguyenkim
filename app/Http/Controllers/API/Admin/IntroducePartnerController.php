<?php

namespace App\Http\Controllers\API\Admin;

use Illuminate\Http\Request;
use App\Models\IntroducePartner;
use App\Http\Controllers\Controller;
use App\Rules\IntroducePartnerRequest;

class IntroducePartnerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //Chương trình khách hàng IntroducePartner
    public function search(Request $request)
    {
        $search = $request->search;
        if($request->search)
        {
            $data = IntroducePartner::where('name', 'LIKE', '%'.$search.'%')->get();
            return response()->json($data);
        }elseif(!isset($request->search)){
            $data = IntroducePartner::all();
            return response()->json($data);
        }else{
            $data = IntroducePartner::all();
            return response()->json($data);
        }
    }
    public function index()
    {
        $listIntroducePartner = IntroducePartner::all();
        return response()->json($listIntroducePartner);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $listIntroducePartner = IntroducePartner::all();
        return response()->json($listIntroducePartner);
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
            $validator = IntroducePartnerRequest::validate($request->all());
            if($validator->fails()){
                return response()->json([
                    'message'=>'Validations fails',
                    'errors'=>$validator->errors()
                ],422);
            }
            $result = IntroducePartner::create($data);
            return response()->json([
                'status'=> true,
                'result' => $result
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
