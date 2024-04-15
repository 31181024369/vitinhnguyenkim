<?php

namespace App\Http\Controllers\API\Member;

use GuzzleHttp\Client;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\BrandDesc;
use App\Models\Menu;
use App\Models\MenuDesc;
use App\Models\CategoryDesc;
use App\Models\ProductCatOptionDesc;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\ProductCatOption;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redis;
use Illuminate\Database\Query\JoinClause;
use App\Models\Adpos;
use App\Models\Advertise;
use App\Models\Price;
use App\Models\ProductProperties;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function selectCategoryChild(Request $request,$slug){
       
        try{
            $catCheck = CategoryDesc::with('category')->where('friendly_url', $slug)->first();
            if($slug=="linh-kien" || $slug=="phu-kien" || (isset($catCheck->category) && $catCheck->category->parentid==11) || (isset($catCheck->category) && $catCheck->category->parentid==12))
            {
                $cat = CategoryDesc::where('friendly_url', $slug)->first();
                if(isset($cat)){
                    $catChild=category::with('categoryDesc','catProperties.properties.propertiesValue')->where('parentid',$cat->cat_id)->get();
                    return response()->json([
                        'status'=>true,
                        'data'=>isset($catChild) ? $catChild :'null'
                    ]);
                }
            }else{
                return response()->json([
                    'status'=>false,
                ]);
            }
            
        }catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'status' =>false
            ]);
        }

    }
    public function menu()
    {
        
        try {
            $menu = Menu::where('display', 1)->where('pos', 'primary')->with('menuDesc')->get();
            $result = [];
            foreach ($menu as $value) {
                
                if(strlen(strstr($value->menuDesc->link, "https://vitinhnguyenkim.vn/")) > 0){
                   
                    $value->menuDesc->link=str_replace('https://vitinhnguyenkim.vn/','',$value->menuDesc->link);
                }
                $arrlink=str_split($value->menuDesc->link);
                if($arrlink[0]=='/'){
                    unset($arrlink[0]);
                    $value->menuDesc->link=implode('',$arrlink);
                }

                if ($value->parentid == 0) {
                    $data = $value;
                    $dataParent = []; 
                    foreach ($menu as $value2) {
                        if ($value2->parentid == $value->menu_id) {
                            $dataParent2 = $value2;
                            $parent = [];
                            foreach ($menu as $value3) {
                                if ($value3->parentid == $value2->menu_id) {
                                    $parent[] = $value3;
                                }
                            }
                            if (isset($parent)) {
                                $dataParent2['parentx'] = $parent;
                            }
                            $dataParent[] = $dataParent2;
                        }
                    }
                    $data['parenty'] = $dataParent;
                    $result[] = $data;
                }
            }
            return response()->json([
                'message' => 'Fetched from database',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'status' =>false
            ]);
        }
    }
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
    
        try {
            // try {
            //     $client = new Client();
            //     $response = $client->get('http://192.168.245.176/api/product-avatar');
            //     $listItemImg = json_decode($response->getBody(), true);
            // } catch ( \Exception $e) {
            //     if ($e->hasResponse()) {
                    
            //         $response = $e->getResponse();
            //         $statusCode = $response->getStatusCode();
            //         $reason = $response->getReasonPhrase();
            //         return response()->json(["message: $statusCode $reason",'status' => false]);
            //     } else {
                    
            //         return response()->json(["message: " . $e->getMessage(),'status' => false]);
            //     }
            // }
          
           
            $catIds = [1, 6, 8, 9, 10, 11, 12, 13, 14, 15, 16, 169, 170];
            $arr = [];
            
            $categories = Category::with('categoryDesc')
                ->select('cat_id', 'parentid')
                ->whereIn('cat_id', $catIds)
                ->get();
            
                // foreach ($categories as $category) {
                //     $products = Product::with(['productDesc' => function ($query) {
                //             $query->select('product_id', 'title','friendly_url'); 
                //         }, 
                //         'brand.brandDesc' => function ($query) {
                //             $query->select('title', 'brand_id');
                //         },
                //         'priceList.propertiesProduct','brand.brandDesc'])
                //         ->select('product_id', 'price', 'price_old', 'position_page','picture')
                //         ->where('stock', 1)
                //         ->whereRaw('FIND_IN_SET(?, cat_list)', [$category->cat_id])
                //         ->paginate(5);
                //     $category->product_child = $products;
                //     $arr[] = $category;
                // }
                
                foreach ($categories as $category) {
                  
                    $products = Product::with(['productDesc' => function ($query) {
                            $query->select('product_id', 'title','friendly_url'); 
                        }, 
                        'brand.brandDesc' => function ($query) {
                                        $query->select('title', 'brand_id');
                                    },
                        'brand.brandDesc' => function ($query) {
                            $query->select('title', 'brand_id');
                        },
                         'priceList.propertiesProduct'])
                        ->select('product_id', 'price', 'price_old', 'position_page','picture','brand_id', 'stock', 'macn')
                        ->where('stock', 1)
                        ->where('display', 1)
                        ->whereRaw('FIND_IN_SET(?, cat_list)', [$category->cat_id])
                        ->paginate(15);
                    $category->product_child = $products;
                    foreach ($products as $key => $value) {
                         $value->technology=$this->getTechnology($value->product_id);
                         $encry =  Crypt::encryptString($value->product_id);
                
                         $encryKey = substr($encry,2);
                         $value->product_id = 0;
                         $value->product_encry_key = $encryKey;
                        
                    }

                   
                    $arr[] = $category;
                }
                
            return response()->json([
                'data' => $arr,
                'status' => true,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'status' => false,
            ]);
        }
    }
    public function search(Request $request)
    {
        try{

            $searchTerm = $request->input('search');
            $data = $request->all();
            $price = $request->price;
            $priceOld = $request->priceOld;
            $category = Category::with('categoryDesc')
                ->whereHas('categoryDesc', function ($query) use ($searchTerm) {
                $query->where('cat_name', 'like', '%' . $searchTerm . '%');
            })->join('product','product_category.cat_id','=','product.cat_id')
            ->where('product.price', 'like', '%' . $price . '%')
            ->orwhere('product.price', 'like', "{$request->price}_%")
            ->orwhere('product.price', 'like', "%_{$request->price}_%")
            ->orwhere('product.price', 'like', "%_{$request->price}")
            ->where('product.price_old', 'like', '%' . $priceOld . '%')
            ->where('status','1')
            ->get();
            $data = [];
            for($i = 0; $i <= count($category); $i++)
            {
                $productID = ($category[$i]['product_id']);
                $encry =  Crypt::encryptString($productID);
                $encryKey = substr($encry,2);
                
                $data = [
                    'catID' => $category[$i]['cat_id'] ??  null,
                    'picture' => $category[$i]['cat_id'] ?? null,
                    'views' => $category[$i]['cat_id'] ?? null,
                    'productId' => $encryKey ?? null,
                ];
            }
            return response()->json([$data]);
        }
        catch(Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    public function detail($id, $sub = null)
    {
        
        try{
            $client = new Client();
            $slug = $id;
            if($sub) {
               $slug = $slug."/".$sub;
            }
            $catId = CategoryDesc::where('friendly_url',$slug)->first()->cat_id;
            
            $response = $client->get('http://mediank.ketnoi365.com/api/category-detail-redis/'.$catId);
            $listItemImg = json_decode($response->getBody(), true);
            $redis = new Client();
            // return response()->json($catId);
            $listCategory = Category::with('product','product.productDesc')->find($catId);
            
            $listProduct = Product::with('productDesc','brand','brandDesc')->where('display',1)->whereRaw('FIND_IN_SET(?,cat_list)',[$catId])->get();

            $adPos=Adpos::where('cat_id', $catId )->get();
            
            $categoryData  = [
                'category' =>['catId' => $listCategory->cat_id,
                'nameCategory' => $listCategory->categoryDesc->cat_name ?? null,
                'homeCategory' => $listCategory->categoryDesc->home_title ?? null,
                'friendUrl' => $listCategory->categoryDesc->friendly_url ?? null,
                'friendTitle' => $listCategory->categoryDesc->friendly_title ?? null,
                'picture' => $listCategory->picture,
                'color' => $listCategory->color,
                'advertise' =>$adPos
            ], 'products' => [],
            
            ];
        
                foreach ($listProduct as $product) {
                    $encry =  Crypt::encryptString($product->product_id);
                    $encryKey = substr($encry,2);
                    if (isset($listItemImg[$product->product_id])) {
                        $product->picture = $listItemImg[$product->product_id];
                    }
                    $productData = [
                        'productId' => $encryKey ?? null,
                        'productTitle' => $product->productDesc->title ?? null,
                        'picture' => $product->picture,
                        'macn' =>$product->macn??null,
                        'brandName' => $product->brandDesc->title ?? null,
                        'price' => $product->price ?? null,
                        'priceOld' => $product->price_old ?? null,
                        'friendUrl' => $product->productDesc->friendly_url ?? null,
                        'friendTitle' => $product->productDesc->friendly_title ?? null,
                        'categoryName' => $product->category->categoryDesc->cat_name
                    ];
                    $categoryData['products'][] = $productData;
            }
       
            return response()->json([
                'status' => true,
                'categoryData' => $categoryData['category'],
                'product' => $categoryData['products'],
            ]);
        // }
        }catch(Exception $e){
            return response()->json([
                'status'=> false,
                'message'=>$e->getMessage(),
            ]);
        }
    }   
    public function listCategoryHome(Request $request)
    {
        try{
            $categories = Category::with('categoryDesc', 'subCategories',
                        'catOption.subCateOption.catOptionDesc')
                ->orderBy('cat_id', 'ASC')->where('parentid',0)->where('display', 1)->get();
            $catOptionlog = $this->buildCatOptionlog($categories);
        
            return response()->json($categories);
        }catch(Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function buildCatOptionlog($categories)
    {
        try{

        $catalog = [];
        foreach ($categories as $category) {
            $catalogItem = [
                'parentid' => $category->parentid,
                'catName' => isset($category->categoryDesc->cat_name)
                    ? $category->categoryDesc->cat_name : 'No Name Category',
                'subCategories' => $this->buildCatOptionlog($category->subCategories),
            ];
            $catalog[] = $catalogItem;
        }
        return $catalog;
        }
        catch(Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    public function listCategoryOption()
    {
        try{

        $listOption = ProductCatOption::with('subCateOption','catOptionDesc')->get();
        $optionData = [];
        foreach ($listOption as $option) {
            $optionData[] = [
                'catId' => $option->cat_id,
                'opId' => $option->op_id,
                'parentid' => $option->parentid,
                'nameOption' => $option->catOptionDesc->title
            ];
        }
        $groupData = [];
        foreach ($optionData as $option) {
            $parentId = $option['parentid'];
            if(!isset($groupData[$parentId])){
                $groupData[$parentId] = [
                    'opId' => $option['opId'],
                    'catId' => $parentId,
                    'nameOption' => $option['nameOption'],
                    'parent' => []
                ];
            }
            $groupData[$parentId]['parent'][] = [
                'catId' => $option['catId'],
                'opId' => $option['opId'],
                'nameOption' => $option['nameOption'],
            ];
        }
            return response()->json([
                'message' => 'Fetched from database',
                'data' => $groupData,
            ]);
        }
        catch(Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function deleteKeyRedis() {
        try{

        $redisKey = 'product_lists';
        Redis::del($redisKey);
        }catch(Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    
}


