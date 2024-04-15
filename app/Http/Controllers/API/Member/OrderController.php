<?php

namespace App\Http\Controllers\API\Member;

use Carbon\Carbon;
use App\Models\OrderSum;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\CategoryDesc;
use App\Models\CardPromotion;
use App\Models\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Models\Coupon;
use App\Models\CouponDes;


class OrderController extends Controller
{
    protected function getModel()
    {
        return new OrderSum();
    }
    public function totalOrderMonth(Request $request){
      
        // $lastMonth=Carbon::now()->subMonth()->month;
        $currentMonth= Carbon::now()->format('m/Y');
       if(isset($request->month)){
        $month=$request->month;
       }
       else{
        $month=$currentMonth;
       }
        $orderSum=OrderSum::with('orderStatus','orderDetail','coupondesusing')
        ->where('mem_id', Auth::guard('member')->user()->mem_id)
        ->get();
        $fromDate=$request['fromDate'];
        $toDate=$request['toDate'];
        foreach($orderSum as $index=> $item){
            if(($timestamp = strtotime($item->date_order)) !== false){
                $value=Carbon::parse($item->date_order)->format('m/d/Y');
               
                $orderSum[$index]['date_order']=$value;
                $orderSum[$index]['month']=Carbon::createFromFormat('m/d/Y',$orderSum[$index]['date_order'])->format('m/Y');
                $orderSum[$index]['searchDay']=Carbon::createFromFormat('m/d/Y',$orderSum[$index]['date_order'])->format('m/d/Y');
            }
            else
            {
                $orderSum[$index]['date_order']=date("m/d/Y",$item->date_order);
                $orderSum[$index]['month']=Carbon::createFromFormat('m/d/Y',$orderSum[$index]['date_order'])->format('m/Y');
                $orderSum[$index]['searchDay']=Carbon::createFromFormat('m/d/Y',$orderSum[$index]['date_order'])->format('m/d/Y'); 
            }
        }
        //return $orderSum;
        $orderSums=[];
        if(isset($fromDate) && isset($toDate)){
            foreach($orderSum as $value){
                if(strtotime($value['searchDay'])>=strtotime($fromDate) && strtotime($value['searchDay'])<=strtotime($toDate)){
                
                    $orderSums[]=$value;
                } 
            }
            
        }
        //return $orderSums;
        $orderSumList=[];
        $countProduct=[];
        
        foreach($orderSum as $index=> $item){
            if($item->month==$month){
                
                $orderSumList[]=$item;
            }
        }
        $totalSum=0;
        $count=count($orderSumList);
        $countSuccsess=0;
        foreach($orderSumList as $sum){
            if($sum->status==5)
            {
                $countSuccsess++;
                $totalSum+=$sum->total_price;
            }
            
        }
       
        $price=number_format($totalSum, 0, '', ',').' VNĐ';
        return response()->json([
            'status'=>true,
            'totalSumMonth'=> $price,
            'totalorder'=>$count,
            'countSuccsessOrder'=>$countSuccsess,
            'month'=>$month
        ]);

    }
    public function index(Request $request)
    {
        try{
            $listOrder = [];
            if (Auth::guard('member')->user()) {
                $listOrder = OrderSum::with('orderStatus', 'orderDetail.product.productDesc','coupondesusing')
                    ->where('mem_id', Auth::guard('member')->user()->mem_id)
                    ->orderBy('order_id', 'DESC');    
            if($request->has('key')){
                $key = $request->has('key');
                $listOrder->where('order_code','=',$key)
                ->orWhereHas('orderDetail', function ($query) use ($key) {
                     $query->where('item_title','LIKE','%'.$key.'%');
                   }); 
            }
            $listOrder =  $listOrder->get();
            foreach ($listOrder as $key => $value) {
                $cardPromotion = CardPromotion::where('order_id', $value['order_id'])->first();
                $listOrder[$key]['coupondesusing']=$value->coupondesusing;
                $listOrder[$key]['card'] = $cardPromotion;
                $listOrder[$key]['order_id'] = ($value->order_id)*99999;

                if(($timestamp = strtotime($value->date_order)) !== false){
                    $value1=Carbon::parse($value->date_order)->format('d-m-Y H:i:s');
                    $listOrder[$key]['date_order']=$value1;
                    //$listOrder[$key]['dateOrder']=Carbon::parse($value->date_order)->format('m-d-Y');
                }
                else
                {
                    $listOrder[$key]['date_order']=date("d-m-Y H:i:s",$value->date_order);
                    //$listOrder[$key]['dateOrder']=date("m-d-Y",$value->date_order);
                }
                //return $listOrder;

                if(count($value->coupondesusing)>0)
                {
                    foreach($value->coupondesusing as $items){
                      $idCoupon=$items->idCouponDes;
                    }
                    $coupounDes=CouponDes::where('idCouponDes',$idCoupon)->get();
                   
                    $listOrder[$key]['coupondesusing'][0]['price']=null;
                    $listOrder[$key]['coupondesusing'][0]['TenCoupon']=null;
                    if(count($coupounDes)>0){
                        foreach($coupounDes as $item1){
                           $idData=$item1->idCoupon;
                        }
                        $price=Coupon::where('id',$idData)->first()->GiaTriCoupon;
                        $name=Coupon::where('id',$idData)->first()->TenCoupon;
                        $listOrder[$key]['coupondesusing'][0]['price']=$price;
                        $listOrder[$key]['coupondesusing'][0]['TenCoupon']=$name;
                    }
                }
              }
              $count=count($listOrder);
              return response()->json([
                  'listOrder'=>$listOrder,
                  'count'=>$count
              ]);
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>'not login'
                ]);
            }
        }
        catch(Exception $e){
                return response()->json([
                    'status' => false,
                    'message' => $e->getMessage()
                ]);
        }
          
        
       
    }
    public function create()
    {

    }
    public function store(Request $request)
    {
        
    }
    public function show(Request $request,$id)
    {
        try{
            $idOrder = $id/99999; 
            $orderDetail = OrderSum::with('orderDetail','orderDetail.product','orderDetail.product.categoryDes','orderDetail.product.brandDesc','coupondesusing')
            ->where('order_id', $idOrder)->first();
            //$categoryDesc = CategoryDesc::get();
            //return  $orderDetail;
            foreach ($orderDetail->orderDetail as $value) {
                $encry =  Crypt::encryptString($value->product->product_id);
                $encryKey = substr($encry, 2);
                $value->product['Id_product']=$encryKey;
                $catList = $value->product->cat_list;
                //return $catList;
                $catListArray = explode(',', $catList);
                $categoryDesc=CategoryDesc::where('cat_id',$catListArray[0])->first();
                $value->product['cat_parent']=$categoryDesc->cat_name;
            }
            if(count($orderDetail->coupondesusing)>0){
                foreach($orderDetail->coupondesusing as $items){
                        
                    $idCoupon=$items->idCouponDes;
                }
                $coupounDes=CouponDes::where('idCouponDes',$idCoupon)->get();
                
                $orderDetail['coupondesusing'][0]['price']=null;
                $orderDetail['coupondesusing'][0]['TenCoupon']=null;
                if(count($coupounDes)>0){
                    
                    foreach($coupounDes as $item1){
                    $idData=$item1->idCoupon;
                    }
                    $price=Coupon::where('id',$idData)->first()->GiaTriCoupon;
                    $name=Coupon::where('id',$idData)->first()->TenCoupon;
                    
                    $orderDetail['coupondesusing'][0]['price']=$price;
                    $orderDetail['coupondesusing'][0]['TenCoupon']=$name;
                
                }

                }
            
                return response()->json($orderDetail);
        }
        catch(Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    


    
    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
    
    }

    public function destroy($id)
    {
        
    }
}