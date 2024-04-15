<?php

namespace App\Http\Controllers\API\Admin;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductDesc;
use App\Models\ProductFlashSale;
use App\Rules\ProductFlashSaleValidate;
use App\Http\Controllers\API\AbstractController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProductFlashSaleController extends Controller
{
   
    protected function getModel()
    {
        
        return new ProductFlashSale();
    }
    public function index()
    {
        
        try {
            $list = ProductFlashSale::with('product','product.productDesc')->get();
            $response = [
                'status' => 'success',
                'list' => $list 
            ];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            $response = [
                'status' => 'false',   
                'error' => $errorMessage
            ];

            return response()->json($response, 500);
        }
      
    }

    public function store(Request $request)
    {
        $product = new Product();
        $productFlashSale = new ProductFlashSale();
        
        try {
            if($request->data != null)
            {
                foreach ($request->data as $id) {
                    $productFlashSale = new ProductFlashSale();
                    $product = Product::with('productDesc')->find($id);
                    $productFlashSale->product_id = $id;
                    $productFlashSale->price = $product->price;
                    $productFlashSale->price_old = $product->price_old;
                    $productFlashSale->discount_percent = 0;
                    $productFlashSale->discount_price = 0;
                    $productFlashSale->start_time = 0;
                    $productFlashSale->end_time = 0;
                    $productFlashSale->status = 1;
                    $productFlashSale-> adminid = Auth::guard('admin')->user()->adminid;
                    $productFlashSale->save();
                }
            }
            else
            {
                return response()->json([
                    'status'=>false,
                ],422);
            }
            return response()->json([
                'status'=>true,
            ],200);
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            $response = [
                'status' => 'false',   
                'error' => $errorMessage
            ];
            return response()->json($response, 500);
        }
    }
    public function destroy($id)
    {
        $arr = explode(",",$id);
        try {
            if($id)
            {
                foreach ($arr as $item) {
                    $list = ProductFlashSale::Find($item)->delete(); 
                }
            }
            else
            {
                return response()->json([
                    'status'=>false,
                ],422);
            }
            return response()->json([
                'status'=>true,
            ],200);
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            $response = [
                'status' => 'false',   
                'error' => $errorMessage
            ];
            return response()->json($response, 500);
        }
        //$list = ProductFlashSale::Find($id)->delete();    
    }
    public function edit($id)
    {
       
        $list = ProductFlashSale::with('product','product.productDesc')->get();
          return response()->json([
            'status'=> true,
            'product' => $list
        ]);
    }
    public function updatedate(Request $request)
    {   
        ProductFlashSale::query()->update(['end_time' => $request->data]);
        return response()->json([
            'status' => true,
            'message' => 'Cập nhật thành công' 
        ]);
    }
}