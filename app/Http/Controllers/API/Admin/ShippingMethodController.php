<?php

namespace App\Http\Controllers\API\Admin;

use Illuminate\Http\Request;
use App\Models\ShippingMethod;
use App\Http\Controllers\Controller;
use App\Rules\ShippingMethodRequest;

class ShippingMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        /**
         * Input: name="search", method="GET", route="order-status-search"
         * Output: list of results
         */
        if(isset($_GET['search'])){
            $search = $_GET['search'];
            $listShippingMethod = ShippingMethod::where('title', 'LIKE', '%'.$search.'%')->get();
            return response()->json([
                'listShippingMethod'=>$listShippingMethod,
                'status' => true
            ]);
        }else{
            return response()->json([
                'status' => false,
               'message' => 'No se encontraron resultados'
            ]);
        }
        return response()->json($listShippingMethod);
    }
    public function index()
    {
        $listShippingMethod = ShippingMethod::all();
        return response()->json($listShippingMethod);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $listShippingMethod = ShippingMethod::all();
        return response()->json($listShippingMethod);
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
            $validator = ShippingMethodRequest::validate($request->all());
            if($validator->fails()){
                return response()->json([
                    'message'=>'Validations fails',
                    'errors'=>$validator->errors()
                ],422);
            }
            $shippingMethod = new ShippingMethod();
            $shippingMethod -> title = $data['title'];
            $shippingMethod -> name = $data['name'];
            $shippingMethod -> description = $data['description'];
            $shippingMethod -> price = $data['price'];
            $shippingMethod -> discount = $data['discount'];
            $shippingMethod -> status = isset($data['status'])? $data['status'] : 0;
            $shippingMethod -> s_type = isset($data['s_type'])? $data['s_type'] : 0;
            $shippingMethod -> s_time = isset($data['s_time'])? $data['s_time'] : 0 ;
            $shippingMethod -> menu_order = isset($data['menu_order'])? $data['menu_order'] : 0;
            $shippingMethod -> display = isset($data['display'])? $data['display'] :1;
            $shippingMethod -> date_post = isset($data['date_post'])? $data['date_post'] :0;
            $shippingMethod -> date_update = isset($data['date_update'])?$data['date_update'] :0;
            $shippingMethod -> lang = isset($data['lang'])?$data['lang']:'vi';
            $shippingMethod -> adminid = isset($data['adminid'])? $data['adminid']:1;
            $shippingMethod -> save();
            return response()->json($shippingMethod);
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
        $shippingMethodId = ShippingMethod::find($id);
        return response()->json($shippingMethodId);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $shippingMethodId = ShippingMethod::findOrFail($id);
        return response()->json($shippingMethodId);
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
            $validator = ShippingMethodRequest::validate($request->all());
            if($validator->fails()){
                return response()->json([
                    'message'=>'Validations fails',
                    'errors'=>$validator->errors()
                ],422);
            }
            $shippingMethod = ShippingMethod::find($id);
            $shippingMethod -> title = $data['title'];
            $shippingMethod -> name = $data['name'];
            $shippingMethod -> description = $data['description'];
            $shippingMethod -> price = $data['price'];
            $shippingMethod -> discount = $data['discount'];
            $shippingMethod -> status = isset($data['status'])? $data['status'] : 0;
            $shippingMethod -> s_type = isset($data['s_type'])? $data['s_type'] : 0;
            $shippingMethod -> s_time = isset($data['s_time'])? $data['s_time'] : 0 ;
            $shippingMethod -> menu_order = isset($data['menu_order'])? $data['menu_order'] : 0;
            $shippingMethod -> display = isset($data['display'])? $data['display'] :1;
            $shippingMethod -> date_post = isset($data['date_post'])? $data['date_post'] :0;
            $shippingMethod -> date_update = isset($data['date_update'])?$data['date_update'] :0;
            $shippingMethod -> lang = isset($data['lang'])?$data['lang']:'vi';
            $shippingMethod -> adminid = isset($data['adminid'])? $data['adminid']:1;
            $shippingMethod -> save();
            return response()->json($shippingMethod);
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
        $shippingMethodId = ShippingMethod::find($id);
        $shippingMethodId -> delete();
        return response()->json(['status', true]);
    }
}
