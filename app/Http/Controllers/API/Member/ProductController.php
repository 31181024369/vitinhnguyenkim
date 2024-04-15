<?php

namespace App\Http\Controllers\API\Member;

use \Exception;
use GuzzleHttp\Client;
use App\Models\Comment;
use App\Models\Star;
use App\Models\Product;
use App\Models\ProductDesc;
use App\Models\CategoryDesc;
use App\Models\ProductGroup;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redis;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\News;
use App\Models\NewsDesc;


use App\Models\StatisticUserOnline;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;
use App\Models\Member;
use App\Models\ProductCatOption;
use App\Models\ProductCatOptionDesc;
use App\Models\StatisticsPages;
use Illuminate\Support\Facades\Auth;
use App\Models\ServiceDesc;
use App\Models\Service;

use App\Models\Products;
use App\Models\Price;
use App\Models\Properties;
use App\Models\ProductProperties;
class ProductController extends Controller
{
    
    public function productHot(Request $request)
    {
        
        $vtnkdt=$request['vtnkdt'];
        if( $vtnkdt==99999){
        try{
            $products = Product::with('price','productDesc','categoryDes')
            ->where('status', 4)->where('stock',1)->where('display',1)->orderBy('product_id', 'desc')->limit(20)->get();
            
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
            $listCatId=[];
            foreach ($products as $key => $idProduct) {
               
                $itemProduct= explode(',',$idProduct->cat_list);
                $catNameParent=CategoryDesc::where('cat_id',$itemProduct[0])->first();

               
                if(!in_array($catNameParent->cat_id,$listCatId))
                {
                    array_push($listCatId,$catNameParent->cat_id);
                    $productList=Product::with('priceList')->whereRaw('FIND_IN_SET(?, cat_list)', $catNameParent->cat_id)->get();
                    $arrProductHot=[];
                    foreach($productList as $item)
                    {
                        $first=explode(',',$item->cat_list);
                        if($item->status==4 && $first[0]==$catNameParent->cat_id){
                            $arrProductHot[]=$item;
                        }
                    }
                   
                    foreach($arrProductHot as $idProduct){
                        $id = $idProduct->product_id;
                        $dataValue=$this->getTechnology($id);

                        $encry =  Crypt::encryptString($id);
                        $encryKey = substr($encry, 2);
                        if (isset($listItemImg[$id])) {
                            $idProduct->picture = $listItemImg[$id];
                        }else{
                            $idProduct->picture = $idProduct->picture;
                        }
                       
                        $idProduct["IdProduct"]=$encryKey;
                        $idProduct->technology=$dataValue;

                      
                        $idProduct["productName"]=$idProduct->productDesc->title;
                        $idProduct["friendlyUrl"]=$idProduct->categoryDes->friendly_url;
                        $idProduct["catNameParent"]=$catNameParent->friendly_url;
                        $idProduct["brandName"]=$idProduct->brandDesc->title;
                        $idProduct["metakey"]=$idProduct->productDesc->metakey;
                        $idProduct["metadesc"]=$idProduct->productDesc->metadesc;


                    }
                   
                    $categoryProductHot[]=[
                        'cat_id'=>$catNameParent->cat_id,
                        'cat_name'=>$catNameParent->cat_name,
                        'home_title'=>$catNameParent->home_title,
                        'friendly_url'=>$catNameParent->friendly_url,
                        'friendly_title'=>$catNameParent->friendly_url,
                        'metakey'=>$catNameParent->metakey,
                        'metadesc'=>$catNameParent->metadesc,
                        'product'=>$arrProductHot
                    ];
                }
              
            }
                return response()->json([
                    'catProductHot'=>$categoryProductHot,
                    'status' => true
                ]);
        }catch(Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }else{
        return response()->json([
            'status'=>'true',
            'data'=>['id'=>123456,'productName'=>'laptop hp','price'=>0]
        ]);

    }
    }
    public function searchProduct()
    {
        try{
        $data = request()->all();
        if($data)
        {
            
            $page = $data['page']?? 1;
            $limit = $data['limit']?? 10;
            $offset = ($page - 1) * $limit;
            //$searchKeywords = explode(' ', $_GET['key']);
            $searchKeywords = $_GET['key'];
            $products = Product::with('priceList','productPicture')
            
            ->whereHas('productDesc', function ($q) use ($searchKeywords) {
                $q->where('macn', 'LIKE', '%' . $searchKeywords . '%')
                ->orWhere('title', 'LIKE', '%' . $searchKeywords . '%');
                
            })->orderBy('product_id', 'desc')->get();
                $services = Service::query()
                ->with('serviceDesc')
                ->whereHas('serviceDesc',function($query) use ($searchKeywords) {
                    // foreach ($searchKeywords as $keyword) {
                        $query->where('title', 'LIKE', '%'.$searchKeywords.'%');
                    // }
                })
                ->orderBy('service_id', 'desc')
                ->get();
           

                try {
                    $client = new Client();
                    $response = $client->get('http://192.168.245.176:8503/api/product-avatar');
                    $listItemImg = json_decode($response->getBody(), true);
                } catch (\Throwable $th) {
                   return response()->json([
                    'status' => false,
                    'message' => $th->getMessage(),
                   ]);
                }
              
                if(count($products)>0)
                {
                    foreach ($products as $product) {
                        if(!empty($listItemImg[$product->product_id])){
                            $product->picture = $listItemImg[$product->product_id];
                        }
                        $price = $product->price;
                        $priceOld = $product->price_old;
                        $picture = $product->picture;
                        foreach ($product->priceList as $row) {
                            if($row->main == 1)
                            {
                                $price = $row->price;
                                $priceOld = $row->price_old;
                                $picture = $row->picture;
                            }
                        }
                        $data1[] = [
                            'productName' => $product->productDesc->title,
                            'price' => $price,
                            'priceOld' => $priceOld,
                            'picture' => $picture,
                            'friendLyUrl' => $product->productDesc->friendly_url,
                            'metakey'=> isset($product->productDesc->metakey) ? $product->productDesc->metakey: 'null',
                            'metadesc'=> isset($product->productDesc->metadesc) ? $product->productDesc->metadesc: 'null'
                        ];
                    }
                }
                else{
                    $data1 =[];
                }
                //return $data1;
                if(count($services)>0){
                    foreach($services as $service){
                        $value=[
                            'productName' => $service->serviceDesc->title."(dịch vụ)",
                            'price' => "service",
                            'priceOld' => "service",
                            'picture' => $service->picture,
                            'friendLyUrl' => $service->serviceDesc->friendly_url,
                            'metakey'=>  isset($service->serviceDesc->metakey) ? $service->serviceDesc->metakey : 'null',
                            'metadesc'=>  isset($service->serviceDesc->metadesc) ? $service->serviceDesc->metadesc :'null'
                        ];
                        
                        $data1[]=$value;
                    }
                }
                else{
                    $data1 =$data1;
                }
                
            return response()->json([
                'product' => $data1,
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
    public function filterProduct()
    {
        try{

        $collection = Product::all();
        $status = $request->status;
        $resultStatus = Product::where('status',$status)->paginate(25);
        $resultSortBy = $collection->sortBy('price')->paginate(25);
        $resultSortDesc = $collection->sortDesc('price')->paginate(25);
        $catId = $request->cat_id ?? '';
        $brandId = $request->brand_id ?? '';
        $priceFrom = $request->price ?? '';
        $priceTo = $request->price ?? '';
        $resultProduct = Product::with('productDesc','category','categoryDes','brand','brand.brandDesc')
                            ->where('cat_id','=',$catId)
                            ->where('brand_id','=',$brandId)
                            ->whereBetween('price',[$priceFrom,$priceTo])
                            ->paginate(25);
        }catch(Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }    

    public function index(Request $request)
    {
       
        try {
            try {
                $client = new Client();
                $response = $client->get('http://192.168.245.176:8503/api/product-avatar');
                $listItemImg = json_decode($response->getBody(), true);
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => false,
                    'message' => $th->getMessage(),
                   ]);
            }
            $product = Product::with('productDesc','categoryDes','brandDesc', 'productPicture')
                ->orderBy('cat_id', 'ASC')->where('display', 1)->paginate(20);
            foreach ($product as $key => $idProduct) {
                $id = $idProduct->product_id;
                $encry =  Crypt::encryptString($id);
                $encryKey = substr($encry, 2);
                if (isset($listItemImg[$id])) {
                    $idProduct->picture = $listItemImg[$id];
                }else{
                    $idProduct->picture = $idProduct->picture;
                }
               
               
               
                $data[] = [
                    'uid' =>$encryKey,
                    'catName' => $idProduct->categoryDes->cat_name,
                    'productName' => isset($idProduct->productDesc->title) ? $idProduct->productDesc->title : "noNameProduct" ,
                    'picture' => $idProduct->picture,
                    'price' => $idProduct->price,
                    'priceOld' => $idProduct->price_old,
                    'brandName' => $idProduct->brandDesc->title,
                    'status' => $idProduct->status,
                    'technology' => $this->getTechnology($id),
                    'views' => $idProduct->views,
                    'url' => $idProduct->url,
                    'friendlyUrl' => isset($idProduct->productDesc->friendly_url)? $idProduct->productDesc->friendly_url: 'null',
                    'friendlyTitle' => isset($idProduct->productDesc->friendly_title)? $idProduct->productDesc->friendly_title:'null',
                    'metakey'=> isset($idProduct->productDesc->metakey) ? $idProduct->productDesc->metakey: 'null',
                    'metadesc'=> isset($idProduct->productDesc->metadesc) ? $idProduct->productDesc->metadesc: 'null'
                ];
            }
        
                return response()->json([
                    'data' => $data,
                ]);
          
        } catch (Exception $e) {
            $errorMessage = $e->getMessage();
            $response = [
                'status' => 'false',   
                'error' => $errorMessage
            ];
            return response()->json($response, 500);
        }
       
    }
    public function getProductAvatar($slug, $id){
        $client = new Client(); 
        try {
            $response = $client->get('http://192.168.245.176:8503/api/product-avatar');
            $responseImgs = $client->get('http://192.168.245.176:8503/api/product-avatar/'.$slug);
            if($response){
                $listItemImg = json_decode($response->getBody(), true);
            }
            if($responseImgs) {
                $listItemImgs = json_decode($responseImgs->getBody(), true);
       
            }
        } catch (\Throwable $th) {
            $client = new Client();
            $response = $client->get('http://192.168.245.176/api/product-avatar');
            $listItemImg = json_decode($response->getBody(), true);
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
   
    public function setStatisticsPages($url,$date){
       
       
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
                        $statisticsPages->module="product";
                        $statisticsPages->action="detail_product";
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
                            'module'=>"product",
                            'action'=>"detail_product",
                            'friendly_url'=>$url
                        ]);
                    }
                }

    }
    public function checkProductDetail(Request $request,$slug){
        
        try{
            $product = ProductDesc::where('friendly_url',$slug)->first();
            if($product ==''){
                $response = [
                    'status' => 'false',   
                    'error' => 'null Product',
                ];
                return response()->json($response, 500);
            }
            else{
                $response = [
                    'status' => 'true',   
                ];
                return response()->json($response, 200);

            }

        }
        catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            $response = [
                'status' => 'false',   
                'error' => $errorMessage,
               
            ];
            return response()->json($response, 500);
        }
    }
    // product detail

    public function detail(Request $request, $slug, Client $client)
    {
        try {
            $responseImgs = $client->get('http://192.168.245.176:8503/api/product-avatar/' . $slug);
            $picture =  json_decode($responseImgs->getBody(), true);
        } catch (\Throwable $th) {
            error_log('Exception caught: ' . $th->getMessage());
        }
        
        $vtnkdt = $request['vtnkdt'];
        if ($vtnkdt == 99999) {
            try {
                
                $productDesc = ProductDesc::where('friendly_url', $slug)->first();
                if(!isset($productDesc)){
                    $response = [
                        'status' => 'false',   
                        'error' => 'null Product',
                    ];
                    return response()->json($response, 500);
                }
               
                $productId = $productDesc->product_id;
    
                $list = Product::with('price', 'productDesc', 'category', 'categoryDes', 'brand', 'brandDesc', 'productPicture')
                    ->where('product_id', $productId)
                    ->firstOrFail();
    
                $catList = $list->cat_list;
                $catArray = explode(",", $catList);
                $firstCat = $catArray[0];
    
                $this->setStatisticsPages($slug, now('Asia/Ho_Chi_Minh'));
               
              
                if ($list->productGroups) {
                    $group = Product::with('productGroups')
                        ->whereHas('productGroups', function ($qr) use ($productId) {
                            $qr->where('product_main', $productId);
                        })
                        ->get();
                  
                    
                       
                    foreach ($group as $product) {
                        
                        $data_groups = [];
                        

                        foreach ($product->productGroups as $productGroup) {
                           
                            $data_group = Product::with('productDesc', 'priceList')
                                ->where('product_id', $productGroup->product_child)
                                ->select('product_id','picture', 'cat_id', 'maso', 'price', 'price_old', 'brand_id', 'status', 'stock', 'votes', 'numvote', 'created_at', 'updated_at')
                                ->first();
                               
                                $encry =  Crypt::encryptString($data_group->product_id);
                               
                                $encryKey = substr($encry, 2);
                               
                               $data_group->encryId = $encryKey;
                               $data_group->product_id = 0;
                            if ($data_group) {
                                $data_groups[] = $data_group;
                            }
                           
                        }
                        $product->data_groups =$data_groups;
                    }
                }
               
               

                $list_news = [];
                if ($list->news_list) {
                    $array = explode(',', $list->news_list);
                    $list_news = News::with('newsDesc')->whereIn('news_id', $array)->get();
                }

                $catNameParent =CategoryDesc::where('cat_id',$list->category->parentid)->first()->cat_name ?? null;
                $catNameParentUrl = CategoryDesc::where('cat_id', $list->category->parentid)->first()->friendly_url ?? null;
                $commentRating = Comment::where('product_id', $productId)->avg('mark');
                $commentRating = round($commentRating);
                $commentProductId = Comment::with('subcomments')
                    ->orderByDesc('comment_id')
                    ->where('parentid', 0)
                    ->where('module', 'product')
                    ->where('display', 1)
                    ->where('post_id', $productId)->get();
    
                $dataValue = $this->getTechnology($productId);
    
                //$stars = Star::where('product_id', $list->product_id)->get();
                $oneStar = 0;
                $twoStar = 0;
                $threeStar = 0;
                $fourStar = 0;
                $fiveStar = 0;
                $average = 0;
                $sumStar = count(Star::where('product_id',$list['product_id'])->get());
                if($sumStar>0)
                {
                    $oneStar = count(Star::where('product_id',$list['product_id'])->where('star',1)->get());
                    $twoStar = count(Star::where('product_id',$list['product_id'])->where('star',2)->get());
                    $threeStar = count(Star::where('product_id',$list['product_id'])->where('star',3)->get());
                    $fourStar = count(Star::where('product_id',$list['product_id'])->where('star',4)->get());
                    $fiveStar = count(Star::where('product_id',$list['product_id'])->where('star',5)->get());
                    $average = (1*$oneStar + 2*$twoStar + 3*$threeStar + 4*$fourStar + 5*$fiveStar) / ($oneStar + $twoStar + $threeStar + $fourStar + $fiveStar);     
                }


                $listPrice = Price::where('product_id', $list->product_id)->get();
    
                $data = [
                    'cat_id' => $firstCat,
                    'catNameParent' => $catNameParent,
                    'catIdParent' => $list->category->parentid,
                    'catNameParentUrl' => $catNameParentUrl,
                    'giftDesc' => optional($productDesc)->gift_desc,
                    'productId' => substr(Crypt::encryptString($list->product_id), 2),
                    'rate' => $commentRating,
                    'productName' => optional($productDesc)->title,
                    'productDescription' => optional($productDesc)->description,
                    'metakey' => optional($productDesc)->metakey ?? 'null',
                    'metadesc' => optional($productDesc)->metadesc ?? 'null',
                    'catName' => optional($list->categoryDes)->cat_name,
                    'urlName' => optional($list->categoryDes)->friendly_url,
                    'brandName' => optional($list->brandDesc)->title,
                    'stock' => $list->stock,
                    'display' => $list->display,
                    'short' => optional($productDesc)->short,
                    'status' => $list->status,
                    'views' => $list->views,
                    'maso' => $list->maso,
                    'data_group' => $group,
                    'url' => $list->url,
                    'friendlyUrl' => optional($productDesc)->friendly_url,
                    'friendlyTitle' => optional($productDesc)->friendly_title,
                    'listPrice' => $listPrice,
                    'price' => $list->price,
                    'priceOld' => $list->price_old,
                    'picture' => $picture,
                    'pictureForDetailProduct' => count($listPrice) > 0 ?  $listPrice[0]->picture : $list->picture,
                    'pictures' => $list->productPicture,
                    'commentProductId' => count($commentProductId) > 0 ? $commentProductId : null,
                    'list_news' => count($list_news) > 0 ? $list_news : null,
                    'star' =>[
                        'oneStar' => $oneStar,
                        'twoStar' => $twoStar,
                        'threeStar' => $threeStar,
                        'fourStar' => $fourStar,
                        'fiveStar' => $fiveStar,
                        'sumStar' => $sumStar,
                        'average' => $average,
                    ],
                    'parameter' => mb_convert_encoding($dataValue, 'UTF-8', 'UTF-8') ?? null,
                ];
    
                return response()->json([
                    'status' => true,
                    'productDetail' => $data,
                    'message' => 'Fetching product from database',
                ]);
            } catch (\Exception $e) {
                $errorMessage = $e->getMessage();
                $response = [
                    'status' => false,
                    'error' => $errorMessage,
                ];
                return response()->json($response, 500);
            }
        } else {
            return response()->json([
                'status' => true,
                'data' => ['id' => 123456, 'productName' => 'laptop hp', 'price' => 0],
            ]);
        }
    }
    
   
}
