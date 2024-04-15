<?php

namespace App\Http\Controllers\API\Admin;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\ComboProduct;
use Illuminate\Http\Request;
use App\Rules\ComboProductRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class ComboProductController extends Controller
{
    public function index()
     {
        $listComboProduct = ComboProduct::where('status',1)->paginate(15);
        $listProduct = Product::with('categoryDes')->paginate(15);
        return response()->json(array(
            'listComboProduct' => $listComboProduct,
            'listProduct' => $listProduct
        ));
     }
 
     /**
      * Show the form for creating a new resource.
      *
      * @return \Illuminate\Http\Response
      */
     public function create()
     {
        $listComboProduct = ComboProduct::paginate(15);
        $listProduct = Product::with('categoryDes')->paginate(15);
        $response = Http::get('https://jsonplaceholder.typicode.com/posts');
        return response()->json(array(
            'listComboProduct' => $listComboProduct,
            'listProduct' => $listProduct
        ));
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
            $validator = ComboProductRequest::validate($request->all());
            if($validator->fails()){
                return response()->json([
                    'message'=>'Validations fails',
                    'errors'=>$validator->errors()
                ],422);
            }
            $data = $request->all();
            /** 
             * Input: get product id 
             */
            $productID = $data['product_id'];
            $getImage = $data['image'];
            $date = Carbon::now('Asia/Ho_Chi_Minh')->isoFormat('DD-MM-YYYY');
            if($getImage)
            {
                $getNameImage = $file->getClientOriginalName();
                $nameImage = current(explode('.',$getNameImage));
                $newImage = $nameProduct.'/'.$date.'-'.rand(0,9999)
                                        .$nameImage .'.' . $file->getClientOriginalExtension();
                $file->move(public_path().'/files/ComboProduct/'.$nameProduct.'/',$newImage);
            }
            $combo = ComboProduct::create([
                $data[''],
            ]);
            return response()->json([
                'status' =>  true,
                'combo' => $combo
            ]);
         }catch(\Throwable $th){
            return response()->json([
                'error' => $th->getMessage(),
                'status' => false
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
         
     }
 
     /**
      * Show the form for editing the specified resource.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function edit($id)
     {
         
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
        
         
     }
 
     /**
      * Remove the specified resource from storage.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function destroy($id)
     {
         
     }
}