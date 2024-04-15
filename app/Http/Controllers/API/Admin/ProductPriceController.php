<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Rules\ProductPriceRequest;
use App\Exports\ProductPriceExport;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class ProductPriceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::guard('member')->check()){
            $listProduct = Product::with('productDesc','category','categoryDes','brand','brandDesc')->paginate(20);
            
            foreach($listProduct as $key => $product)
            {
                $data[] = [
                    'categoryName' => $product->categoryDes->cat_name,
                    'productName' => $product->productDesc->title,
                    'price' => $product->price
                ];
            }
            return response()->json([
                'status' => true,
                'data' => $data
            ]);
        }else{
            return response()->json([

                'status' => false,
                'message' => 'You need to login to use this function!'
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Auth::guard('member')->check()){         
            $data = [];
            $product = $request->product;
            $price = $request->price;
            $category = $request->category;
            $quantity = $request->quantity;
            $reducePrice = $request->reducePrice;
            for($i = 0 ; $i < count($product); $i++){
                array_push($data,[
                    "product_id" => $product[$i],
                    "price" =>$price[$i],
                    'category' => $category,
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'quantity' => $quantity[$i],
                    'reducePrice' => $reducePrice[$i],
                ]);
            }
            $file = 'exports/bang-bao-gia.xlsx';
            $export = new ProductPriceExport($data);
            Excel::store($export, $file, 'public');
            $fileUrl = Storage::url($file);
                return response()->json([
                    "status" => "true",
                ]);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'You need to login to use this function!'
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