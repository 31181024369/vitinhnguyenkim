<?php

namespace App\Http\Controllers\API\Admin;

use GuzzleHttp\Client;
use App\Models\Product;
use App\Models\NewsDesc;
use App\Models\ProductDesc;
use App\Models\ProductCatOptionDesc;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use App\Models\Price;
use App\Models\ProductProperties;

class RelatedProductController extends Controller
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
    public function index(Request $request)
    {
        try{
            $client = new Client();
            $slug = $request->key;
            $response = $client->get('http://192.168.245.176:8503/api/product-avatar/'.$slug);
            $listItemImg = json_decode($response->getBody(), true);
            $friendUrl = ProductDesc::where('friendly_url',$slug)->first()->product_id;
            $productId = $friendUrl;
            //return $productId;
            $productItem = Product::with('priceList','productDesc','category','categoryDes','brand','brandDesc','productPicture')->find($productId);
            //return $productItem;
            $priceList = $productItem->price;
            foreach ($productItem->priceList as $key => $row) {
                if($row->main == 1)
                {
                    $priceList = $row->price_old;
                }
            }
            $priceMin = $priceList*0.9;
            $priceMax = $priceList*1.1;
            
            $relatedProduct = Product::with('priceList','productDesc')
                                    ->where('product_id','!=', $productId)
                                    // ->orWhereNull('product_id')
                                    ->where('cat_id',$productItem->cat_id)
                                    ->WhereHas('priceList',function ($subQuery) use ($priceMin,$priceMax) {
                                        $subQuery->whereBetween('price', [$priceMin, $priceMax]);
                                    })
                                    ->where('stock',1)
                                    ->orderBy('product_id','DESC')
                                    ->inRandomOrder()->take(4)->get();
            //return  $relatedProduct;  
            $data =[];              
            foreach ($relatedProduct as $product) {
                
                if (isset($listItemImg[$product->product_id])) {
                    $product->picture = $listItemImg[$product->product_id];
                }
                $data_technology = $this->getTechnology($product->product_id);

                $picture = $product->picture;
                $price = $product->price;
                $priceOld = $product->price_old;
                foreach ($product->priceList as $key => $rows) {
                    if($rows->main == 1)
                    {
                        $picture = $rows->picture;
                        $price = $rows->price;
                        $priceOld = $rows->price_old;
                    } 
                }

                $data[] = [
                    'productId' => substr(Crypt::encryptString($product->product_id), 2),
                    'productName' => $product->productDesc->title ?? null,
                    'picture' => $picture ?? null,
                    'price' => $price ?? null,
                    'priceOld' => $priceOld ?? null,
                    'friendlyUrl' => $product->productDesc->friendly_url ?? null,
                    'maCn' => $product->macn ?? null,
                    'brandName' => $product->brandDesc->title ?? null,
                    'catName' => $product->categoryDes->cat_name ?? null,
                    'Status' => $product->stock ?? null,
                    'technology' => $data_technology ?? null
                ];
            }
                                    
            return response()->json([
                'relatedProduct' => $data,
                'status' => true
            ]);
        }catch(Exception $e){
            return response()->json([
              'status' => false,
              'message' => $e->getMessage()
            ]);
        }
        
    }
    public function productProposal(Request $request)
    {
        $client = new Client();
        $response = $client->get('http://192.168.245.176:8503/api/product-avatar/');
        $listItemImg = json_decode($response->getBody(), true);
        /**friend_url product */
        $slug = $request->key;
        $friendUrl = ProductDesc::where('friendly_url',$slug)->first()->product_id;
        $productId = $friendUrl;
        $productItem = Product::with('productDesc','category','categoryDes','brand',
                    'brandDesc','productPicture')->find($productId);
        $productCategories = Product::with('category','categoryDes')
                                        ->whereNotIn('cat_id',[$productItem->cat_id])
                                        ->where('status',1)
                                        ->inRandomOrder()->get();
        foreach($productCategories as $product)
        {
            if(isset($listItemImg[$product->product_id])){
                $product->picture = $listItemImg[$product->product_id];
            }
            $data [] = [
                'productId' => substr( Crypt::encryptString($product->product_id),2),
                'productName' => $product->productDesc->title ?? null,
                'picture' => $product->picture ?? null,
                'price' => $product->price?? null,
                'priceOld' => $product->price_old?? null,
                'friendlyUrl' => $product->productDesc->friendly_url?? null,
            ];
        }
        return response()->json([
            'productOtherCategories' => $data
        ]);
    }
    public function productRelatedBrand(Request $request)
    {
        /**friend_url product */
        $client = new Client();
        $response = $client->get('http://192.168.245.176:8503/api/product-avatar/');
        $listItemImg = json_decode($response->getBody(), true);
        /**friend_url product */
        $slug = $request->key;
        $friendUrl = ProductDesc::where('friendly_url',$slug)->first()->product_id;
        $productId = $friendUrl;
        $productItem = Product::with('productDesc','category','categoryDes','brand',
                    'brandDesc','productPicture')->find($productId);
        $productIdBrand = Product::with('brand','brandDesc')
                                    ->whereNotIn('brand_id',[$productItem->brand_id])
                                    ->where('status',1)
                                    ->orderBy('product_id','desc')
                                    ->inRandomOrder()->get();
        foreach($productIdBrand as $product)
        {
            if(isset($listItemImg[$product->product_id])){
                $product->picture = $listItemImg[$product->product_id];
            }
            $data [] = [
                'productId' => substr( Crypt::encryptString($product->product_id),2),
                'productName' => $product->productDesc->title ?? null,
                'picture' => $product->picture ?? null,
                'price' => $product->price?? null,
                'priceOld' => $product->price_old?? null,
                'friendlyUrl' => $product->productDesc->friendly_url?? null,
            ];
        }
        return response()->json([
            'productOtherBrand' => $data,
            'status' => true
        ]);

    }
    /**Post Relate */
    public function postRelate(Request $request)
    {
        $data = [];
        $client = new Client();
        $response = $client->get('http://192.168.245.176:8503/api/product-avatar/');
        $listItemImg = json_decode($response->getBody(), true);
        $id = Crypt::decryptString('ey'.$request->key);

        if($id) {
            
        $productItem = Product::with('productDesc','category','categoryDes','brand',
        'brandDesc','productPicture')->find($id);
        $news = NewsDesc::with('news')->whereRaw('FIND_IN_SET(?, product_id)', [$id])->orderBy('news_id','DESC')->get();

        foreach($news as $value)
        {
            if(isset($listItemImg[$value->product_id]) && $value->news != null){
                $value->news->picture = $listItemImg[$value->product_id];
            }
            $data[] = [
                'Id' => $value->news_id,
                'title' => $value->title,      
                'friendlyUrl' => $value->friendly_url,
                'friendlyTitle' => $value->friendly_title,
                'picture' => $value->news?$value->news->picture:'No_image.jpg',
            ];
        }
        return response()->json([
            'data' => $data ,
            'status' => true
        ]);
      }else{
        return response()->json([
            'data' =>'id not found',
            'status' => false
        ]);
      }
    }
    
}
