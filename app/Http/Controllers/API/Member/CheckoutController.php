<?php

namespace App\Http\Controllers\API\Member;

use Carbon\Carbon;
use App\Models\OrderSum;
use App\Models\CouponDes;
use App\Models\CouponDesUsing;
use App\Models\OrderDetail;
use App\Models\ListCart;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\Models\CardPromotion;
use App\Rules\OrderMemberRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderEmail;
use App\Models\Member;
use App\Jobs\sendEmailOrderJob;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        try{
            DB::beginTransaction();
            $data = $request->all();
            $validator  = OrderMemberRequest::validate($data);
            if($validator->fails()){
                return response()->json([
                    'status' => 'false',
                    'message' => $validator->errors()
                ]);
            }
            
                $orderNew = new OrderSum();
                $orderNew -> order_code = rand(9,9999)
                                    .Carbon::now('Asia/Ho_Chi_Minh')->isoFormat('DDMMYYYY');
                $orderNew -> d_name = Auth::guard('member')->user() ? Auth::guard('member')->user()->username : $data['d_name'];
                $orderNew -> d_address = Auth::guard('member')->user() ? Auth::guard('member')->user()->address : $data['d_address'];
                $orderNew -> d_phone = Auth::guard('member')->user() ? Auth::guard('member')->user()->phone : $data['d_phone'];
                $orderNew -> d_email = Auth::guard('member')->user() ? Auth::guard('member')->user()->email : $data['d_email'];
                $orderNew -> c_name = $data['c_name'] ?? '';
                $orderNew -> c_address = $data['c_address'] ?? '';
                $orderNew -> c_phone =  Auth::guard('member')->user() ? Auth::guard('member')->user()->phone : $data['d_phone'];
                $orderNew -> c_email = $data['c_email'] ?? '';
                $orderNew -> total_cart = $data['total_cart'];
                $orderNew -> total_price = $data['total_price'];
                $orderNew -> shipping_method = $data['shipping_method'];
                $orderNew -> payment_method = $data['payment_method'];
                $orderNew -> status = 1;
                $orderNew -> date_order = Carbon::now('Asia/Ho_Chi_Minh')->isoFormat('DD-MM-YYYY HH:mm:ss');
                $orderNew -> comment = $data['comment']??'';
                $orderNew -> note = $data['note'] ?? '';
                $orderNew -> mem_id = Auth::guard('member')->user() ? Auth::guard('member')->user()->mem_id : 0;
                $orderNew -> CouponDiscout = $data['CouponDiscout'] ?? 0;
                $orderNew -> diem_use = $data['diem_use'] ?? 0;
                $orderNew -> status_diem = 0;
                $orderNew -> update_at = Carbon::now('Asia/Ho_Chi_Minh')->isoFormat('DD-MM-YYYY HH:mm:ss');
                $orderNew -> save();
               
              $temp = count($data['orders']);
              
              $dataProduct=[];
                for($i = 0 ; $i <  $temp; $i++){
                   if(Auth::guard('member')->user()){
                        $price=isset($data['orders'][$i]['listPrice']) ? $data['orders'][$i]['listPrice'][0]['price_old']:$data['orders'][$i]['price'];
                   }
                   else{ 

                        $price=isset($data['orders'][$i]['listPrice']) ? $data['orders'][$i]['listPrice'][0]['price']:$data['orders'][$i]['price'];
                   }

                    $productId = Auth::guard('member')->user()?$data['orders'][$i]['product_id']:$data['orders'][$i]['productId'];

                    $orderDetail = new OrderDetail();
                    $orderDetail -> order_id = $orderNew -> order_id;
                    $orderDetail -> item_id = Crypt::decryptString('ey'.$productId);
                    $orderDetail -> quantity = $data['orders'][$i]['quality'];
                    $orderDetail -> item_title = Auth::guard('member')->user()?$data['orders'][$i]['title']:$data['orders'][$i]['productName'];
                    $orderDetail -> item_price = $price;
                    $orderDetail -> subtotal = $data['orders'][$i]['quality'] * ($price);
                    $dataProduct[]=[
                        'productId'=>$productId,
                        'order_id'=>$orderDetail -> order_id,
                        'item_price'=>$orderDetail ->item_price,
                        'item_id'=> $orderDetail -> item_id,
                        'quantity'=>$orderDetail -> quantity,
                        'item_title'=>$orderDetail -> item_title,
                        'subtotal'=>$orderDetail -> subtotal 
                    ];
                    $orderDetail->save();
                    if(Auth::guard('member')->user() != "")
                    {
                    $mem_id = Auth::guard('member')->user()->mem_id;
                    ListCart::where('mem_id', $mem_id)->where('product_id', Crypt::decryptString('ey'.$productId))->delete();
                    }
                }
             
                
               
               
                // if isset MaCouponDes then subtract the number of uses
                $dataCoupon=null;
                if(isset($data['maCoupon'])){
                    $couponDes = CouponDes::where('MaCouponDes',$data['maCoupon'])->where('SoLanConLaiDes','>',0)->first();
                   if($couponDes) {
                    $couponDesItem = $couponDes->idCouponDes;
                    $couponDescription = CouponDes::find($couponDesItem);
                    $couponDescription->SoLanSuDungDes += 1;
                    if($couponDescription->SoLanConLaiDes >= 0)
                    {
                        $couponDescription->SoLanConLaiDes -= 1;
                    }
                    $couponDescription->save();
                    $usingCoupon = new CouponDesUsing();
                    $usingCoupon -> IDuser = Auth::guard('member')->user() ? Auth::guard('member')->user()->mem_id : 0;
                    $usingCoupon -> idCouponDes = $couponDesItem;
                    $usingCoupon -> DateUsingCode = Carbon::now('Asia/Ho_Chi_Minh');
                    $usingCoupon -> IDOrderCode = $orderNew -> order_code;
                    $usingCoupon -> MaCouponUSer = $data['maCoupon'];
                    $usingCoupon ->save();
                    $dataCoupon=[
                        'idCouponDes'=>$couponDesItem,
                        'SoLanSuDungDes'=> $couponDescription->SoLanSuDungDes,
                        'SoLanConLaiDes'=>$couponDescription->SoLanConLaiDes,
                        'DateUsingCode'=> $usingCoupon -> DateUsingCode ,
                        'MaCouponUSer'=> $usingCoupon -> MaCouponUSer,
                    ];
                   }
                }
              
               
               
                $data=[
                    'd_code'=>$orderNew -> order_code,
                    'd_name'=> Auth::guard('member')->user()?Auth::guard('member')->user()->username:$data['d_name'],
                    'd_adress'=>Auth::guard('member')->user()?Auth::guard('member')->user()->address:$data['d_address'],
                    'd_phone'=> Auth::guard('member')->user()?Auth::guard('member')->user()->phone:$data['d_phone'],
                    'd_gmail'=>  Auth::guard('member')->user()?Auth::guard('member')->user()->email:$data['d_email'],
                    'total_cart'=> $orderNew -> total_cart,
                    'total_price'=>$orderNew -> total_price,
                    'CouponDiscout'=> $orderNew -> CouponDiscout,
                    'listProduct'=> $dataProduct,
                    'coupon'=> $dataCoupon,
                    // 'cardPromotion'=>$priceCard
                ];
               
                Mail::to(Auth::guard('member')->user()?Auth::guard('member')->user()->email:$request->d_email)
               ->send(new OrderEmail($data));

               Mail::to('long542.nt@gmail.com')
               ->send(new OrderEmail($data));

        //        $dataOrder=[
        //         'name'=>Auth::guard('member')->user()?Auth::guard('member')->user()->email:$request->d_email,
        //         'data'=>$data
        //     ];

      
        // try{
        //     dispatch(new sendEmailOrderJob($dataOrder));
        // }catch(Exception $e){
        //     return response()->json([
        //         'status' => false,
        //         'message' => $e->getMessage(),
        //     ]);
        // }

           
           
            
            DB::commit();
            return response()->json([
                'status' => true,
            ]);
        }catch(Exception $e){
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
}