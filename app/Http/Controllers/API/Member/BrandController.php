<?php

namespace App\Http\Controllers\API\Member;

use App\Models\Brand;
use GuzzleHttp\Client;
use App\Models\Product;
use App\Models\Category;

use App\Models\BrandDesc;
use App\Models\CategoryDesc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use App\Models\Price;
use App\Models\ProductProperties;
use App\Models\PropertiesCategory;

class BrandController extends Controller
{
    public function searchCategoryProduct(Request $request)
    {
        try{

        $idBrand = $request->brand;
        $idCate = $request->key;
        // $productCateId = CategoryDesc::where('cat_id', $idCate)->first()->cat_id;
        
        // $productBrandId = BrandDesc::where('brand_id', $idBrand)->first()->brand_id;
        $productPrice = $request->price;
        $productPriceOld = $request->price_old;
        // $productPriceOver = $request->price_over;
        // $productPriceMin = $request->price_min;
        $query = Product::query();
       
        if(!empty($productPrice) && !empty($productPriceOld))
        {
            $query=Product::whereRaw('FIND_IN_SET(?, cat_list)', [$idCate])
            ->where('brand_id', $idBrand)
                    ->whereBetween('price',[$productPrice,$productPriceOld])->get();
        }
        try {
            $client = new Client();
            $response = $client->get('http://192.168.245.176:8503/api/product-avatar');
            $listItemImg = json_decode($response->getBody(), true);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
             ]
            );
        }
        foreach($query as $value)
        {
            if (isset($listItemImg[$value->product_id])) {
                $value->picture = $listItemImg[$value->product_id];
            }
            $productId = Crypt::encryptString($value->product_id);

            $data[] = [
                'productId' => Crypt::encryptString($value->product_id),
                'productName' => $value->productDesc->title ?? null,
                'productId' => substr($productId,2) ?? null,
                'pictureForDetailProduct' => $value->picture ?? null,
                'price' => $value->price ?? null,
                'price_old' => $value->price_old?? null,
                'friendlyUrl' => $value->productDesc->friendly_url ?? null,
                'metakey'=>$value->productDesc->metakey ?? null,
                'metadesc'=>$value->productDesc->metadesc ?? null,
            ];
        }
            return response()->json($data ?? null);
        }catch(Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    // public function getTechnology($id){

    //     $price=Price::where('product_id', $id)->where('main',1)->first();
    //     $dataOp=[];
     
    //     if(isset($price) && is_object(json_decode($price->technology)))
    //     {
    //         foreach(json_decode($price->technology) as $catOption=> $nameCatOption)
    //         {
    //                 array_push($dataOp, [
    //                     'catOption' => $catOption ?? '',
    //                     'nameCatOption' => $nameCatOption ?? null,
    //                 ]);
                
    //         }
    //     }
    //     // unset($dataOp[count($dataOp)-1]);
    //     return $dataOp;
    // }

    public function checkProperties($properties_id,$id){
        $propertiesProduct=ProductProperties::with('properties','propertiesValue')->where('price_id',$id)->get();
        foreach($propertiesProduct as $value){
            if($properties_id==$value->properties_id){
                return ($value->description!=null ? $value->description : (isset($value->propertiesValue) ? $value->propertiesValue->name: ''));
            }
        }
        return false;

    }
   
    public function getTechnology($id){
        $price=Price::where('product_id', $id)->where('main',1)->first();
        $product = Product::where('product_id',$id)->first();
        $cat_id = explode(",",$product->cat_list);
                    
        $category = PropertiesCategory::with('properties','properties.propertiesValue')->where('cat_id',$cat_id[0])->get();
        
        $dataOp=[];
        if(isset($price))
        {
            foreach($category as $item){
                array_push($dataOp, [
                    'catOption' =>isset($item->properties) ? $item->properties->title : '',
                    'nameCatOption' =>$this->checkProperties($item->properties_id,$price->id)? $this->checkProperties($item->properties_id,$price->id) :''
                ]);
            }
        }
        return $dataOp;
    }
    public function index()
    {
        try {
            $list = Brand::with('brandDesc','product','product.productDesc','product.categoryDes')->get();
            foreach($list as $key => $value) {
                $id= $value->brand_id;
                $encry =  Crypt::encryptString($id);
                $encryKey = substr($encry,2);
                $encryKeyProduct = substr($encryKey,2);
                $data[] = [
                    'brandId' => $id,
                    'picture' => $value->picture,
                    'title' => $value->brandDesc->title,
                    'friendlyUrl' => $value->brandDesc->friendly_url,
                    'metakey'=>$value->brandDesc->metakey ?? null,
                    'metadesc'=>$value->brandDesc->metadesc ?? null,
                    'catName' => isset($value->product->categoryDes) ? $value->product->categoryDes->cat_name : 'No Category Name' ,
                    'productId' => isset($value->product) ? $value->product->product_id : 'No product ID', 
                    'nameProduct' => isset($value->product->productDesc) ? $value->product->productDesc->title : 'No Name', 
                    'pictueProduct' => isset($value->product) ? $value->product->picture : 'No picture', 
                ];
            }
            $response = [
                'status' => true,
                'list' => $data 
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
    
    public function listCategory($idCategory)
    {
        try{
            $categories = Category::with('categoryDesc')->where('cat_id',$idCategory)->get();
            $catalog = $this->buildCatalog($categories);
        
            return response()->json($catalog);
        }catch(Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    public function buildCatalog($categories)
    {
        try{
            $catalog = [];
            foreach ($categories as $category) {
                $listBrand=[];
                $array = explode(',', $category->list_brand);
                $data=[];
                if($array!=null){
                    foreach($array as $item){
                        if($item!=""){
                            $listBrand[]=[
                                'id'=>$item,
                                'title'=>BrandDesc::where('id',$item)->first()->title,
                                'friendly_url'=>BrandDesc::where('id',$item)->first()->friendly_url,
                                'metakey'=> BrandDesc::where('id',$item)->first()->metakey ?? null,
                                'metadesc'=> BrandDesc::where('id',$item)->first()->metadesc ?? null
                            ];
                        }
                    }
                }
                $id = $category->cat_id;
                $catalogItem = [
                    'id'=>$category->cat_id,
                    // 'parentid' => $category->parentid,
                    'friendUrl' => isset($category->categoryDesc->friendly_url)
                        ? $category->categoryDesc->friendly_url : 'No Friendly',
                    'catName' => isset($category->categoryDesc->cat_name)
                        ? $category->categoryDesc->cat_name : 'No Name Category',
                    'metakey'=> $category->categoryDesc->metakey ?? null,
                    'metadesc'=> $category->categoryDesc->metadesc ?? null,
                    'subCategories' => $this->buildCatalog($category->subCategories),
                    'listBrand'=>$listBrand,
                ];
                $catalog[] = $catalogItem;
            }
            return $catalog;
        }catch(Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function compareProducts(Request $request)
    {
        
        try{

            $key1 = Crypt::decryptString('ey'.$request->key1);
            $key2 = Crypt::decryptString('ey'.$request->key2);
          
            if($request->key3){
                $key3 = Crypt::decryptString('ey'.$request->key3);
            }
            $ids = [
                'key1' => $key1,
                'key2' => $key2,
                'key3' => $key3 ?? null
            ];
            $data = [];
            $client = new Client();
            $response = $client->get('http://192.168.245.176:8503/api/product-avatar');
            $listItemImg = json_decode($response->getBody(), true);
            foreach ($ids as $index => $id) {
                if (!$id) {
                    continue;
                }
                $info = Product::with('productDesc')->where('product_id', $id)
                    ->join('product_category_desc', 'product_category_desc.cat_id', '=', 'product.cat_id')
                    ->join('product_brand_desc', 'product_brand_desc.brand_id', '=', 'product.brand_id')
                    ->select(
                        '*',
                        'product_category_desc.cat_name as product_category',
                        'product_brand_desc.title as product_brand'
                    )
                    ->first();
                    $dataValue=$this->getTechnology($id);
                   
               
                // $dataTechnology = $info["technology"];
                // $dataTechnology = preg_replace_callback(
                //     '/(?<=^|\{|;)s:(\d+):\"(.*?)\";(?=[asbdiO]\:\d|N;|\}|$)/s',
                //     function($m){
                //         return 's:' . strlen($m[2]) . ':"' . $m[2] . '";';
                //     },
                //     $dataTechnology
                // );
                // $dataResult = unserialize($dataTechnology);

                // $options = DB::table('product_cat_option_desc')->get();
                // $dataValue = [];
                // foreach ($options as $option) {
                //     foreach ($dataResult as $key => $value) {
                //         if ($option->op_id == $key) {
                //             array_push($dataValue, [
                //                 'catOption' => $option->title ?? '',
                //                 'nameCatOption' => $value ?? null
                //             ]);
                //         }
                //     }
                // }
                if (isset($listItemImg[$info->product_id])) {
                        $info->picture = $listItemImg[$info->product_id];
                    }
                $data[] = [
                    'picture' => $info->picture,
                    'price' => $info['price'],
                    'priceOld' => $info['price_old'],
                    'friendUrl' => $info['friend_url'],
                    'catName' => $info['cat_name'],
                    'productName' => $info->productDesc->title,
                    'productBrand' => $info['product_brand'],
                    'dataTechnology'  => mb_convert_encoding($dataValue, 'UTF-8', 'UTF-8') ?? null,
                ];
            }
           
            return response()->json([
                'data' => $data,
            ]);
        }catch(Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
       
    }
    

}
