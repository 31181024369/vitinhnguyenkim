<?php

namespace App\Http\Controllers\API\Member;
use Carbon\Carbon;
use App\Models\Member;
use App\Models\Product;
use App\Models\Coupon;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RepurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{

        if (!empty(Auth::guard('member')->user()->mem_id)) {
            $listRepurchase = Member::with('repurchase.orderDetail.product.productDesc')
                ->where('mem_id', Auth::guard('member')->user()->mem_id)
                ->get();
            $data = [];
            foreach ($listRepurchase as $key => $value) {
                foreach ($value->repurchase as $repurchase) {
                    $orderDetail = $repurchase->orderDetail;
                    if ($orderDetail) {
                        $product = $orderDetail->product;
                        if ($product) {
                            $productDesc = $product->productDesc;
                            $data[] = [
                                'picture' => $productDesc->title ?? null,
                                'quantity' => $orderDetail->quantity ?? null,
                                'url' => $orderDetail->item_title ?? null,
                                'subtotal' => $orderDetail->subtotal ?? null,
                            ];
                        }
                    }
                }
            }
            return response()->json([
                'additionalData' => $data
            ]);
        }
        }catch(Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
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
        try{

        $date_now=strtotime(Carbon::now('Asia/Ho_Chi_Minh')->toDayDateTimeString());
        //return $request->all();
        if (!empty(Auth::guard('member')->user()->mem_id)) {
            $listRepurchase = Member::with('repurchase.orderDetail.product.productDesc')
            ->where('mem_id', Auth::guard('member')->user()->mem_id)
            ->first();
            //return $listRepurchase;
            $order_detail=[];
            foreach ($listRepurchase->repurchase as $value) {
                $orderDetail = $value->orderDetail;
                //$order_detail=[];
                    $product=$orderDetail->product;
                    if($product->product_id==$request->id){
                       $stock= $product->stock;
                        if($stock==0)
                        {
                            return response()->json([ 
                                    'message' => 'Sản phẩm đã hết hàng ',
                                    'status'=>false
                            ]);
                        }
                        else
                       {
                        // Auth::guard('member')->user()->mem_id  1675
                        //->where('MaPhatHanh',$request->macoupon)
                            $listCouponForYou = Coupon::with('couponDesc')->orderBy('id','DESC')->where('status_id',1)
                                ->whereRaw('FIND_IN_SET(?,mem_id)',Auth::guard('member')->user()->mem_id)->where('MaPhatHanh',$request->macoupon)->first();
                        //return  $listCouponForYou;
                            if($listCouponForYou)
                            {
                                switch ($listCouponForYou){
                                    
                                    case ($listCouponForYou->KHSuDungToiDa==$listCouponForYou->SoLanSuDung):
                                        return response()->json([ 
                                            'message' => 'Coupon của bạn đã vượt số lần sử dụng ',
                                            'status'=>false
                                        ]);
                                        break;
                                    case ($date_now<$listCouponForYou->StartCouponDate || $date_now>$listCouponForYou->EndCouponDate):
                                        return response()->json([ 
                                            'message' => 'Coupon của bạn đã hết hạn sử dụng ',
                                            'status'=>false
                                        ]);
                                        break;
                                }
                                $listCouponForYou->SoLanSuDung=$listCouponForYou->SoLanSuDung+1;
                                $item_price=($orderDetail->item_price)*($request->quantity)-$listCouponForYou->GiaTriCoupon;
                                $subtotal=$item_price;
                            }
                            else
                            {
                                $item_price=($orderDetail->item_price)*($request->quantity);
                                $subtotal=$item_price;
                            }
                            $order_detail=[
                                "id"=> $orderDetail->id,
                                "order_id"=> $orderDetail->order_id,
                                "item_type"=> $orderDetail->item_id,
                                "item_id"=>$orderDetail->item_id,
                                "quantity"=> $request->quantity,
                                "item_price"=> $item_price,
                                "subtotal"=>$subtotal,
                                "add_from"=>"web",
                                "product" =>$product
                            ];
                        //return $order_detail;   
                    }
                }
            }
            $listRepurchase['repurchase'][]=$order_detail;
            //return  $listRepurchase;
            return response()->json([
                'data' => $listRepurchase,
                'status' =>true
            ]);
        }
        }catch(Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
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
