<?php

namespace App\Http\Controllers\API\Member;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\ProductFlashSale;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use App\Models\CategoryDesc;
use Illuminate\Support\Facades\DB;
use App\Models\Price;
use App\Models\ProductProperties;
class FlashSaleController extends Controller
{
    
    public function getTechnology($id){
        $price=Price::where('product_id', $id)->where('main',1)->first();
        // return $price
        $dataOp=[];
        if(isset($price))
        {
            $propertiesProduct=ProductProperties::with('properties','propertiesValue')->where('price_id',$price->id)->get();
           
            foreach($propertiesProduct as $value){
                    array_push($dataOp, [
                        'catOption' => isset($value->properties) ? $value->properties->title : '',
                        'nameCatOption' => $value->description!=null ? $value->description : (isset($value->propertiesValue) ? $value->propertiesValue->name: '')
                    ]);
            }
        }
        return $dataOp;
    }
   
    public function index()
    {
        try{
            $data = [];

            $now = Carbon::now()->format('d/m/Y');
            $listFlashSaleProduct = ProductFlashSale::with('product','product.productDesc','product.categoryDes')
                                    ->where('status',1)
                                    ->orderBy('id','DESC')
                                    ->get();
            $startTime=null;
            $endTime=null;
           
            //return $listFlashSaleProduct;
            foreach($listFlashSaleProduct as $productFlash)
            {
                   
                    if(isset($productFlash->product))
                    {
                        if($productFlash->product->stock==1){ 
                        $itemProduct= explode(',',$productFlash->product->cat_list);
                        if($itemProduct[0]==null){
                            $itemProduct[0]=1;
                        }
                        $catNameParent=CategoryDesc::where('cat_id',$itemProduct[0])->first();
                        $dataValue=$this->getTechnology($productFlash->product_id);
                        $data[] = [
                            'productId' => substr(Crypt::encryptString($productFlash->product_id),2),
                            'price' => $productFlash->price,
                            'price_old' => $productFlash->price_old,
                            'discount_percent' => $productFlash->discount_percent,
                            'discount_price' => $productFlash->discount_price,
                            'productName' => $productFlash->product->productDesc?$productFlash->product->productDesc->title : "",
                            'picture' => $productFlash->product->picture,
                            'time' => $productFlash->time,
                            'friendlyUrl' => $productFlash->product->productDesc?$productFlash->product->productDesc->friendly_url:"",
                            'friendlyTitle' => $productFlash->product->productDesc ?$productFlash->product->productDesc->friendly_title :"",
                            'catName' => $productFlash->product->category->categoryDesc?$productFlash->product->category->categoryDesc->cat_name:"",
                            'catNamePa' => $catNameParent,
                            'status' => $productFlash->status,
                            'brandName'=>$productFlash->product->brandDesc->title,
                            'stock'=>$productFlash->product->stock,
                            'macn'=>$productFlash->product->macn,
                            'technology'=>$dataValue
                            
                        ];
                        $startTime=$productFlash->start_time;
                        $endTime=$productFlash->end_time;
                        }
                    }
            }
            return response()->json([
                'data' =>  $data,
                'startTime'=>$startTime,
                'endTime'=>$endTime,
                // 'time'=>$time
            ]);
        }catch(Exception $e)
        {
            return response()->json([
              'status' => false,
              'message' => $e->getMessage()
            ]);
        }
    }
    public function show(){
       
        try{
            $now = Carbon::now();
            $nowFormatted = $now->format('m/d/Y');
            $listFlashSaleProduct = ProductFlashSale::where('status', 1)->get();
            foreach ($listFlashSaleProduct as $value) {
                $timeStart=Carbon::parse($value->start_time)->format('m/d/Y');
                $timeEnd=Carbon::parse($value->end_time)->format('m/d/Y');
                if (  strtotime($timeStart)> strtotime($nowFormatted) || strtotime($timeEnd) < strtotime($nowFormatted)) {
                    $value->status=0;
                    $value->save();
                    
                }
            }
            return response()->json(['status'=>true]);
        }
        catch(Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
              ]);
        }
    }
}
