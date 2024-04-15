<?php

namespace App\Http\Controllers\API\Member;

use Illuminate\Support\Str;
use App\Models\Brand;
use App\Models\PropertiesCategory;
use GuzzleHttp\Client;
use App\Models\Product;
use App\Models\Category;
use App\Models\BrandDesc;
use App\Models\CategoryDesc;
use Illuminate\Http\Request;
use App\Models\ProductCatOption;
use App\Http\Controllers\Controller;
use App\Models\ProductCatOptionDesc;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use App\Models\StatisticsPages;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

use App\Models\Price;
use App\Models\Properties;
use App\Models\ProductProperties;
use Illuminate\Support\Facades\Redis;

//paginate
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class FilterCategoryController extends Controller
{

    
    public static function paginate($items, $perPage = 5, $page = null)
     {
         $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
         $total = count($items);
         $currentpage = $page;
         $offset = ($currentpage * $perPage) - $perPage ;
         $itemstoshow = array_slice($items , $offset , $perPage);
         
         return new LengthAwarePaginator($itemstoshow ,$total   ,$perPage);
    }
    public function setStatisticsPages($url,$date,$module,$action){
       
        if(Auth::guard('member')->user())
                {
                    $mem_id=Auth::guard('member')->user()->mem_id;
                    $statisticsPages=StatisticsPages::where('id',$mem_id)->where('uri',$url)->first();
                    if(isset($statisticsPages)){
                        $statisticsPages->date=$date;
                        $statisticsPages->count=$statisticsPages->count+1;
                        $statisticsPages->save();
                    }else{
                        $statisticsPages=new StatisticsPages;
                        $statisticsPages->id=$mem_id;
                        $statisticsPages->uri=$url;
                        $statisticsPages->date=$date;
                        $statisticsPages->count=1;
                        $statisticsPages->module=$module;
                        $statisticsPages->action=$action;
                        $statisticsPages->friendly_url=$url;
                        $statisticsPages->save();
                    }
                }
                else{
                    $statisticsPages=StatisticsPages::where('id', 0)->where('uri',$url)->first();
                    if(isset($statisticsPages))
                    {
                        $statisticsPages->date=$date;
                        $statisticsPages->count=$statisticsPages->count+1;
                        $statisticsPages->save();
                    }else{
                        $statisticsPages=StatisticsPages::create([
                            'uri'=>$url,
                            'date'=>$date,
                            'count'=>1,
                            'module'=>$module,
                            'action'=>$action,
                            'friendly_url'=>$url
                        ]);
                    }
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
        
        try{
        $categoryId = $request->key;
        if($categoryId != "") {
            //list_Category
            $category = Category::with('categoryDesc', 'catProperties.properties.propertiesValue')
            ->where('cat_id', $categoryId)
            ->first();

            //filter_option
            $data =[];
            foreach ($category->catProperties as $key => $value) {
                if(count($value->properties->propertiesValue)>0)
                {
                    $propertiesValue = [];
                    
                        foreach ($value->properties->propertiesValue as $ky => $item) {
                            $slugItem = Str::slug($item->name, '-');
                            $saveItem = [
                                'id' => $item->id,
                                'properties_id' => $item->properties_id,
                                'name' => $item->name,
                                'slug' => $slugItem,
                            ];
                            $propertiesValue[] = $saveItem;
                        }
                    
                    $slug = Str::slug($value->properties->title, '-');
                    $save = [
                        'id' => $value->properties->id,
                        'title' => $value->properties->title,
                        'slug' => $slug,
                        'propertiesValue' => $propertiesValue,
                    ];
                    $data[] = $save; 
                }
            }
            //filter_Brand
            $idBrand = explode(',',$category->list_brand);
            $listBrand = Brand::with('BrandDesc')->whereIn('brand_id',$idBrand)->get();
            $responseData = [
                'list' =>  $listBrand,
                'options' => $data
            ];
        
            return response()->json($responseData);
          
        }
        
        }catch(Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                
            ]);
        }
    }
    public function cateChildOption(Request $request){
        try{
            $categoryId = $request->key;
            
            if($categoryId != "") {
                $category = Category::with('categoryDesc', 'catChildProperties.properties.propertiesValue')
                ->where('cat_id', $categoryId)
                ->first();
                $data =[];
                
                foreach ($category->catChildProperties as $key => $value) {
                        $propertiesValue = [];
                        foreach ($value->properties->propertiesValue as $ky => $item) {
                            $slugItem = Str::slug($item->name, '-');
                            $saveItem = [
                                'id' => $item->id,
                                'properties_id' => $item->properties_id,
                                'name' => $item->name,
                                'slug' => $slugItem,
                            ];
                            $propertiesValue[] = $saveItem;
                        }
                    
                    $slug = Str::slug($value->properties->title, '-');
                    $save = [
                        'id' => $value->properties->id,
                        'title' => $value->properties->title,
                        'slug' => $slug,
                        'propertiesValue' => $propertiesValue,
                    ];
                    $data[] = $save; 
                }
                return response()->json([
                    'status'=>true,
                    'data'=>$data
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
    

    public function buildCategoryMenu($categories)
    {
        try{
        $catalog = [];
        foreach ($categories as $category) {
            $catalogItem = [
                'parentid' => $category->parentid,
                'catName' => isset($category->categoryDesc->cat_name)
                    ? $category->categoryDesc->cat_name : 'No Name Category',
                'subCategories' => $this->buildCategoryMenu($category->subCategories),
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
  

    public function filter(Request $request)
    { 

            $vtnkdt=$request['vtnkdt'];
            $client = new Client();
            $responseImgs = $client->get('http://192.168.245.176:8503/api/product-avatar/');
            $listItemImgs = json_decode($responseImgs->getBody(), true);
            if( $vtnkdt==99999)
            {
                try{ 
                    $params = json_decode($request->params, true);
                  
                    $totalProductForFilter = "";
                  
                    $offset = $request->page ? $request->page : 1 ;
                    $minPrice = $request->from;
                    $maxPrice = $request->to;
                    $sort = $request->sort != "" ? $request->sort : 'DESC';
                    $listTech = [];
                    $search = $request->search;
                    if(empty($search))
                    {
                        if(empty($request->keySlugCate))
                        {
                            $arrProductHot= Product::with('price.propertiesProduct.properties','productDesc','category','categoryDes', 'brand', 'brandDesc')
                            ->where('status', 4)
                            ->where('stock',1)->where('display',1)
                            ->orderBy('product_id', 'desc')->paginate(15);

                            return response()->json([
                                'productHot' => $arrProductHot,
                                'status'=>true,
                            ]);
                            
                        }
                        else{

                            $status=true;
                            $sortView = $request->sortView != "" ? $request->sortView : 'DESC';
                            $sortStatus = $request->sortStatus;
                            $itemPage = $request->item ?  $request->item : 20 ;
                            $cat = CategoryDesc::with('category')->where('friendly_url', $request->keySlugCate)->first();
                            // return $cat;
                            if(!isset($cat)){
                                return response()->json([
                                    'status'=>false,
                                    'message'=>'category null'
                                ]);
                            }
                            $catId =$cat->cat_id;
                            $date = Carbon::now('Asia/Ho_Chi_Minh');
                            if($cat->category->parentid==0)
                            {
                                $this->setStatisticsPages($request->keySlugCate,$date,'category','category');
                            }
                            
                            $catNameParent = Category::with('CategoryDesc')->where('cat_id',$catId)->first();
                            $query = Product::with('priceList.propertiesProduct.properties'
                            ,'productDesc', 'category.subCategories', 'categoryDes', 'brandDesc')
                            ->whereRaw('FIND_IN_SET(?, cat_list)', [$catId])
                            ->orderByRaw("
                                CASE
                                    WHEN stock = 1 THEN 1
                                    WHEN stock = 2 THEN 2
                                    ELSE 3
                                END
                            ");

                            if(isset($params) && count($params) > 0) {
                                foreach ($params as $key => $value) {
                                  
                                    if (isset($value['id']) && isset($value['properties_id'])) {
                                    
                                        $query->where(function ($query) use ($value) {
                                        $query->orWhereHas('price.propertiesProduct', function ($subQuery) use ($value) {
                                        $subQuery->where(function ($subQuery) use ($value)
                                        {
                                            $subQuery->where('pv_id', $value['id'])->orWhere('description',  $value['name']);
                                        })
                                            ->where('properties_id', $value['properties_id']);
                                            });
                                        });
                                    }
                                }      
                            }

                          
                            $totalProduct = count(Product::whereRaw('FIND_IN_SET(?, cat_list)', [$catId])->get());
                            if(isset($request['brand_id'])){
                                
                                $query->where('brand_id',$request['brand_id']);

                            }
                            if (!empty($sortStatus)) {
                                $query->where('status',$sortStatus)->where('stock',1); 
                              
                            }
                            if (!empty($minPrice) && !empty($maxPrice)) {
                                
                                $query->WhereHas('price',function ($subQuery) use ($minPrice,$maxPrice) {
                                        $comparePrice = auth('member')->user() ? "price_old" : "price";
                                        $subQuery->whereBetween($comparePrice, [$minPrice, $maxPrice]);
                                });
                            }
                            if (!empty($catId)) {
                                $query->whereRaw('FIND_IN_SET(?,cat_list)', [$catId]);
                    
                            }
                            if(!empty($sort)) {
                                $query->join('price', 'price.product_id', '=', 'product.product_id')
                                    ->orderBy('price.price_old', $sort);
                                                        
                            }
                           
                            $totalProductForFilter = count($query->get());
                            $listDataProduct=  $query->limit(10)
                            ->offset(($offset-1)*10)->get();
                            $listProduct = [];
                            foreach ($listDataProduct as $key => $value) {
                                
                                $encry = Crypt::encryptString($value->product_id);
                                $encryKey = substr($encry, 2);
                                
                                $value['productId'] = $encryKey;
                                $value['technology'] = $this->getTechnology($value->product_id);
                              
                                $listProduct[] = $value;
                            

                            }

                            $catProduct = CategoryDesc::where('friendly_url',$request->keySlugCate)->first();
                            if(!isset($catProduct)){
                            
                                $part = explode("-", $request->keySlugCate);
                                $brandIsset=BrandDesc::where("friendly_url",$part[1])->first();
                                if(!isset($brandIsset)){
                                    $status=false;
                                }
                            }else{
                                $part = $request->keySlugCate;
                                
                            }
                            $catProduct = CategoryDesc::where('friendly_url',$part)->first()->cat_id;
                        
                            $categoryList=Category::where('cat_id', $catProduct)->first()->cat_code;
                            
                            $listParent=explode("_", $categoryList);
                            
                            $dataListParent=[];
                        
                            $count=count($listParent);
                            
                            
                            foreach($listParent as $index=> $item){
                                $dataListParent[]=CategoryDesc::where('cat_id',$item)->first();
                            }
                            $categoryListChild=Category::where('parentid', $catProduct)->get()->pluck('cat_id');
                            $dataListChild=[];
                            foreach($categoryListChild as $item){
                                $dataListChild[]=CategoryDesc::where('cat_id',$item)->first();
                            
                            }
                            return response()->json([
                                
                                'products' => $listProduct,
                                 'catname' => $catNameParent,
                                'dataListParent'=> $dataListParent,
                                'dataListChild'=>$dataListChild,
                                'status'=>$status,
                                'message'=>$status==false? 'brand null': '',
                                'pageTitle' => $cat->metadesc,
                                'total' => $totalProduct,
                                'totalProductForFilter' => $totalProductForFilter,
                                'pictureForDetailProduct' => "product/laptop/HP/hp-15s-i3-15s-fq5228tu-8u240pa.png"
                            ]);
                        }
                    }

                    // search 
                    else
                    {
                        $query = Product::with('priceList.propertiesProduct.properties','productDesc', 'category', 'categoryDes', 'brandDesc')
                        ->where('macn', 'like', '%' . $search . '%')
                        ->orWhereHas('productDesc', function ($qr) use ($search) {
                            $qr->where('title', 'like', '%' . $search . '%');
                        })
                        ->orderByRaw("
                            CASE
                                WHEN stock = 1 THEN 1
                                WHEN stock = 2 THEN 2
                                ELSE 3
                            END
                        ");
                        $catList=$query->get();
                        $data=[];
                        foreach($catList as $item)
                        {
                                if(!in_array($item->cat_id,$data)){
                                    array_push($data,$item->cat_id);
                                }
                                
                        }
                        $categories=Category::with('categoryDesc', 'subCategories',
                            'catOption.subCateOption.catOptionDesc')
                            ->orderBy('cat_id', 'ASC')->where('parentid',0)->where('display', 1)
                            ->get();
                        $menuCategory=$this->buildCategoryMenu( $categories);
                        
                        $query->orderBy('price',$sort);
                            
                        $listProduct=$query->where('display',1)->paginate(15);
                        
                    
                        foreach ($listProduct as $key => $value) {
                            $listProduct[$key]['technology'] = $this->getTechnology($value->product_id);
                            $catArray = explode(',', $value->cat_list);
                            $catNameParent = CategoryDesc::where('cat_id',$catArray)->get();
                            $encry =  Crypt::encryptString($value->product_id);
                            $encryKey = substr($encry, 2);
                            $listProduct[$key]['id_product'] = $encryKey;
                            $listProduct[$key]['product_id'] = 0;
                            $listProduct[$key]['catNameParent'] = $catNameParent;
                        }
                        $catProduct = CategoryDesc::where('friendly_url',$request->keySlugCate)->first();
                        return response()->json([
                            'products' => $listProduct,
                            'categoryMenu'=>$menuCategory,
                            'catNameParent' => null
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
    
    }

   
    
}