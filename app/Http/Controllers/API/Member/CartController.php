<?php

namespace App\Http\Controllers\API\Member;

use Carbon\Carbon;
use App\Models\CrawData;
use App\Models\ListCart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\API\Member\AbstractController;
use App\Models\Product;
use App\Models\ProductDesc;
use App\Models\CategoryDesc;
use App\Models\Brand;
use App\Models\BrandDesc;


class CartController extends AbstractController
{
    
    protected function getModel()
    {
        return new ListCart();
    }
    public function index()
    {
        
        $data = [];
       if(auth('member')->user() != null) {
        try {
            $date = Carbon::now('Asia/Ho_Chi_Minh')->isoFormat('DD-MM-YYYY-HH:mm:ss');
            $list = ListCart::where("mem_id",auth('member')->user()->mem_id)
            ->get();
            foreach($list as $cart)
                {
                    $id = $cart['product_id'];
                    $encry =  Crypt::encryptString($id);
                    
                    $encryKey = substr($encry,2);
                    // $catId=CategoryDesc::where('cat_name',$cart['cat_name'])->first()->cat_id;
                   
                    
                    // //return $id;
                    // $productRelated=Product::inRandomOrder()->with('productDesc')
                  
                    // ->whereRaw('FIND_IN_SET(?, cat_list)', [$catId])
                    // ->limit(20)->get();
                   

                    $data[] = [
                        'id'=>$cart->id,
                        'name' => $cart->mem_name ?? null,
                        'friendlyUrlProduct' => $cart->productDesc->friendly_url??null,
                        'price' => $cart['price'] ?? null,
                        'quality' => $cart['quality'] ?? null,
                        'product_id' => $encryKey ?? null,
                        'macn' => $cart['macn'] ?? null,
                        'picture' => $cart['picture'] ?? null,
                        'cat_name' => $cart['cat_name'] ?? null,
                        'cat_name_parent' => $cart['cat_name_parent'] ?? null,
                        'title' => $cart['title'] ?? null,
                        'brand_name' => $cart['brand_name'] ?? null,
                        'status' => $cart['status'] ?? null,
                        'stock' => $cart['stock'] ?? null,
                        'productRelated'=> $productRelated?? null
                    ];  
                }
            $response = [
                'status' => true,
                'data' => $data
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
    }
    public function create()
    {
        try{

        $list = parent::create();
        return response()->json([
            'data' => $list         
        ]);
        }catch(Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    public function store(Request $request)
    {
        try{
            $arrPicture = "";
            if (is_array($request->picture)) {
                $arrPicture = $request->picture[0];
            } else {
                $arrPicture =  $request->picture ;
            }
            $data = $request->all();
            $productIdEncrypt = "";
            
            if(gettype($data['product_id']) == 'integer'){
                $productIdEncrypt =$data['product_id'] / 99999;
            }else{
                $productIdEncrypt =Crypt::decryptString('ey'. $data['product_id']);
            }
            if(!empty(Auth::guard('member')->user()->mem_id))
            { 
               
                
                $cartMember= ListCart::where('product_id',$productIdEncrypt)
                ->where('mem_id',Auth::guard('member')->user()->mem_id)->first();
                if($cartMember)
                {
                    $cartMember->quality = $cartMember['quality']+1;
                    $cartMember->price = $data['price'];
                    $cartMember->save();
                    
                }
                else
                {
                   

                $cartMember = new ListCart();
                $cartMember -> mem_id = Auth::guard('member')->user()->mem_id;
                $cartMember -> mem_name = Auth::guard('member')->user()->username;
                $cartMember -> product_id =$productIdEncrypt;

                $cartMember -> stock =$request->stock;
                
                // $cartMember -> product_id = $productIdDecrypt;
                $cartMember -> macn = $request->MaKhoSPApdung;
                $cartMember -> brand_name = $request->brandName;
                $cartMember -> picture = $arrPicture;
                $cartMember -> cat_name = $request->catName;
                $cartMember -> title = $request->title;
                $cartMember -> cat_name_parent	 = $request->catNameParent;
                $cartMember -> quality = $request->quality;
                $cartMember -> price =$request->price;

                $cartMember -> status = 1;
                $cartMember->save();
            }
            return response()->json([
                'data' => $cartMember,
                'status' => true
            ]);
            }
            else{
                $list = parent::store($request);
                return response()->json([
                    'datas' => $list,
                    'status' => true
                ]);
            }
        }catch(Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
        
    }
    public function show(Request $request,$id)
    {
        try{
        $list = parent::show($id);
        return response()->json([
            'data' => $data[] = [
                'name' => $list['name'],
                'friendlyUrlProduct' => $list->productDesc->friendly_url??null,
                'price' => $list['price'],
                'quality' => $list['quality'],
                'status' => $list['status'],
            ]               
        ]);
        }catch(Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    public function edit($id)
    {
        try{
        $list = parent::edit($id);
        return response()->json([
            'data' => $data[] = [
                'id' => $list['id'],
                'name' => $list['name'],
                'friendlyUrlProduct' => $list->productDesc->friendly_url??null,
                'price' => $list['price'],
                'quality' => $list['quality'],
                'status' => $list['status'],
            ]       
        ]);
        }
        catch(Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    public function update(Request $request, $id)
    {
        try{
            if(!empty(Auth::guard('member')->user()->mem_id))
            {
                $data = $request->all();
                $productIdEncrypt = $data['product_id'];
                $productIdDecrypt = Crypt::decryptString('ey'.$productIdEncrypt);
                $cartMember = ListCart::findOrFail($id);
                $cartMember -> mem_id = Auth::guard('member')->user()->mem_id;
                $cartMember -> mem_name = Auth::guard('member')->user()->username;
                $cartMember -> product_id = $productIdDecrypt;
                // $cartMember -> product_id = $data['product_id'];
                $cartMember -> quality = $data['quality'];
                $cartMember -> price = $data['price'];
                $cartMember -> title = $data['title'];
                $cartMember -> status = 1;
                $cartMember -> save();
                return response()->json([
                    'data' => $cartMember,
                    'status' => true
                ]);
            }else{
                //** $list = parent::update($id);*/ 
                $data = $request->all();
                /**$list = parent::store($request);*/
                $productIdEncrypt = $data['product_id'];
                $productIdDecrypt = Crypt::decryptString('ey'.$productIdEncrypt);
                $cartMember = ListCart::findOrFail($id);
                $cartMember -> product_id = $productIdDecrypt;
                $cartMember -> quality = $data['quality'];
                $cartMember -> price = $data['price'];
                $cartMember -> title = $data['title'];
                $cartMember -> status = 1;
                $cartMember -> save();
                return response()->json([
                    'data' => $cartMember,
                    'status' => true
                ]);
            }
        }catch(Exception $e){
            return response()->json([
               'status' => false,
               'message' => $e->getMessage()
            ]);
        }
    }
    
    public function destroy($id)
    {
        try{
            $listId = parent::destroy($id);
            return response()->json([
                'status' => true
            ]);
        }catch(Exception $e){
            return response()->json([
              'status' => false,
              'message' => $e->getMessage()
            ]);
        }
    }
}