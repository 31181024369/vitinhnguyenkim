<?php

namespace App\Http\Controllers\API\Admin;

use Carbon\Carbon;
use App\Models\OrderStatus;
use Illuminate\Http\Request;
use App\Rules\OrderStatusRequest;
use App\Http\Controllers\Controller;

class OrderStatusController extends Controller
{
        /**
         * Input: name="search", method="GET", route="order-status-search"
         * Output: list of results
         */
    public function search(Request $request)
    {
        dd($request->search);
        if(isset($_GET['search'])){
            $search = $_GET['search'];
            $listOrderStatus = OrderStatus::where('title', 'LIKE', '%'.$search.'%')->get();
            return response()->json($listOrderStatus);
        }else{
            return response()->json([
                'message' => 'Invalid search parameters  provided for this search term.',
                'status' => true
            ]);
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $listOrderStatus = OrderStatus::all();
        return response()->json($listOrderStatus);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function create()
    {
        $listOrderStatus = OrderStatus::all();
        return response()->json($listOrderStatus);
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
            $validator = OrderStatusRequest::validate($request->all());
            if($validator->fails()){
                return response()->json([
                    'message'=>'Validations fails',
                    'errors'=>$validator->errors()
                ],422);
            }
            $orderStatus = new OrderStatus();
            $orderStatus -> title = $data['title'];
            $orderStatus -> color = $data['color'];
            $orderStatus -> date_post = isset($data['date_post']) ? $data['date_post']: '0';
            $orderStatus -> date_update = isset($data['date_update']) ? $data['date_update']: '0';
            $orderStatus -> is_default = isset($data['is_default']) ? $data['is_default']: '0' ;
            $orderStatus -> is_payment = isset($data['is_payment']) ? $data['is_payment']:'0';
            $orderStatus -> is_complete = isset($data['is_complete']) ? $data['is_complete']:'0';
            $orderStatus -> is_cancel = isset($data['is_cancel']) ? $data['is_cancel']:'0';
            $orderStatus -> is_customer = isset($data['is_customer']) ? $data['is_customer']:'0';
            $orderStatus -> menu_order = isset($data['menu_order']) ? $data['menu_order']:'0';
            $orderStatus -> display = isset($data['display']) ? $data['display']:'1';
            $orderStatus -> lang = 'vi';
            $orderStatus -> adminid = isset($data['adminid']) ? $data['adminid']:0;
            $orderStatus -> save();
            return response()->json([
                'orderStatus'=>$orderStatus,
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
        $oderStatusId = OrderStatus::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $oderStatusId = OrderStatus::findOrFail($id);

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
            $validator = OrderStatusRequest::validate($request->all());
            if($validator->fails()){
                return response()->json([
                    'message'=>'Validations fails',
                    'errors'=>$validator->errors()
                ],422);
            }
            $orderStatusId = OrderStatus::find($id);
            $orderStatusId -> title = $data['title'];
            $orderStatusId -> color = $data['color'];
            $orderStatusId -> date_post = isset($data['date_post']) ? $data['date_post']: '0';
            $orderStatusId -> date_update = isset($data['date_update']) ? $data['date_update']: '0';
            $orderStatusId -> is_default = isset($data['is_default']) ? $data['is_default']: '0' ;
            $orderStatusId -> is_payment = isset($data['is_payment']) ? $data['is_payment']:'0';
            $orderStatusId -> is_complete = isset($data['is_complete']) ? $data['is_complete']:'0';
            $orderStatusId -> is_cancel = isset($data['is_cancel']) ? $data['is_cancel']:'0';
            $orderStatusId -> is_customer = isset($data['is_customer']) ? $data['is_customer']:'0';
            $orderStatusId -> menu_order = isset($data['menu_order']) ? $data['menu_order']:'0';
            $orderStatusId -> display = isset($data['display']) ? $data['display']:'1';
            $orderStatusId -> lang = 'vi';
            $orderStatusId -> adminid = isset($data['adminid']) ? $data['adminid']:0;
            $orderStatusId -> save();
            return response()->json([
                'orderStatusId'=>$orderStatusId,
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
        $orderStatusId = OrderStatus::find($id);
        $orderStatusId -> delete();
        return response()->json([
                    'status'=>true,
                ]);
    }
}
