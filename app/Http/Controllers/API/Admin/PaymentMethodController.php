<?php

namespace App\Http\Controllers\API\Admin;

use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Http\Controllers\Controller;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $listPaymentMethod = PaymentMethod::all();
        return response()->json($listPaymentMethod);
    }

    /**
     * Show the form for creating a new resource.
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
            $listPaymentMethod = PaymentMethod::where('title', 'LIKE', '%'.$search.'%')->get();
            return response()->json($listPaymentMethod);
        }else{
            return response()->json([
                'message' => 'Invalid search parameters  provided for this search term.',
                'error' => true
            ]);
        }
    }
    public function create()
    {
        $listPaymentMethod = PaymentMethod::all();
        return response()->json($listPaymentMethod);
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
            $validator = PaymentMethodRequest::validate($request->all());
            if($validator->fails()){
                return response()->json([
                    'message'=>'Validations fails',
                    'errors'=>$validator->errors()
                ],422);
            }
            $PaymentMethod = new PaymentMethod();
            $PaymentMethod -> title = $data['title'];
            $PaymentMethod -> name = $data['name'];
            $PaymentMethod -> description = isset($data['description']) ? $data['description']: 'NULL';
            $PaymentMethod -> options = isset($data['options']) ? $data['options']: 'NULL';
            $PaymentMethod -> is_config = isset($data['is_config']) ? $data['is_config']: '0' ;
            $PaymentMethod -> menu_order = isset($data['menu_order']) ? $data['menu_order']:'0';
            $PaymentMethod -> display = isset($data['display']) ? $data['display']:'0';
            $PaymentMethod -> lang = isset($data['lang']) ? $data['lang']:'vi';
            $PaymentMethod -> date_post = isset($data['date_post']) ? $data['date_post']: '0';
            $PaymentMethod -> date_update = isset($data['date_update']) ? $data['date_update']: '0';
            $PaymentMethod -> display = isset($data['display']) ? $data['display']:'1';
            $PaymentMethod -> adminid = isset($data['adminid']) ? $data['adminid']:'NULL';
            $PaymentMethod -> save();
            return response()->json([
                'PaymentMethod'=>$PaymentMethod,
                'status'=>true,
            ]);
        }catch(Throwable $th){
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
        $oderStatusId = PaymentMethod::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $oderStatusId = PaymentMethod::findOrFail($id);

        return response()->json($oderStatusId);
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
            $validator = PaymentMethodRequest::validate($request->all());
            if($validator->fails()){
                return response()->json([
                    'message'=>'Validations fails',
                    'errors'=>$validator->errors()
                ],422);
            }
            $PaymentMethodId = PaymentMethod::find($id);
            $PaymentMethod -> title = $data['title'];
            $PaymentMethod -> name = $data['name'];
            $PaymentMethod -> description = isset($data['description']) ? $data['description']: 'NULL';
            $PaymentMethod -> options = isset($data['options']) ? $data['options']: 'NULL';
            $PaymentMethod -> is_config = isset($data['is_config']) ? $data['is_config']: '0' ;
            $PaymentMethod -> menu_order = isset($data['menu_order']) ? $data['menu_order']:'0';
            $PaymentMethod -> display = isset($data['display']) ? $data['display']:'0';
            $PaymentMethod -> lang = isset($data['lang']) ? $data['lang']:'vi';
            $PaymentMethod -> date_post = isset($data['date_post']) ? $data['date_post']: '0';
            $PaymentMethod -> date_update = isset($data['date_update']) ? $data['date_update']: '0';
            $PaymentMethod -> display = isset($data['display']) ? $data['display']:'1';
            $PaymentMethod -> adminid = isset($data['adminid']) ? $data['adminid']:'NULL';
            $PaymentMethod -> save();
            return response()->json([
                'PaymentMethodId'=>$PaymentMethodId,
                'status'=>true,
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
        $PaymentMethodId = PaymentMethod::find($id);
        $PaymentMethodId -> delete();
        return response()->json([
                    'status'=>true,
                ]);
    }
}
