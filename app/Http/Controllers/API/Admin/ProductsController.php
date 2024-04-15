

<?php

//namespace App\Http\Controllers\API\Admin;

// use Carbon\Carbon;
// use App\Models\Product;
// use App\Models\Price;
// use App\Models\ProductDesc;
// use App\Models\Brand;
// use App\Models\BrandDesc;
// use App\Models\Category;
// use App\Models\CategoryDesc;
// use Illuminate\Http\Request;
// use App\Rules\ProductValidate;
// use App\Imports\ProductsImport;
// use App\Observers\ProductObserver;
// use App\Http\Controllers\Controller;
// use Illuminate\Http\Client\Response;
// use Illuminate\Pagination\Paginator;
// use Illuminate\Support\Facades\Http;
// use Illuminate\Support\Facades\Cache;
// use Illuminate\Support\Facades\Crypt;
// use Illuminate\Support\Facades\Redis;
// use App\Models\ProductGiftDescription;
// use PhpOffice\PhpSpreadsheet\IOFactory;
// use Illuminate\Pagination\LengthAwarePaginator;
// use App\Http\Controllers\API\AbstractController;
// use Illuminate\Contracts\Encryption\DecryptException;
// use App\Models\ListCart;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Auth;
// use Maatwebsite\Excel\Facades\Excel;


// class ProductController extends Controller
// {
//     public function __construct()
//     {
//         Product::observe(ProductObserver::class);
//     }
//     protected function getModel()
//     {
//         return new Product();
//     }
//     public function productAdvertise(Request $request){
       
        

//         if ($request->hasFile('picture')) {
//             $file = $request->file('picture');
//             $fileName = time() . '_' . $file->getClientOriginalName();
//             $picturePath = $request->file('picture')->store('product'. '\\'. 'advertise'.'\\'. $fileName, 'public');
//         } else {   
//             $picturePath = ''; 
//         }
//         $productAdvertise=DB::table('product_advertise')->insert([
//             'title' => $request->title,
//             'picture' => $picturePath,
//             'link'=>$request->link,
//             'target'=>$request->target,
//             'width'=>$request->width,
//             'height'=>$request->height,
//             'description'=>$request->description,
//             'display'=>$request->display,
//             'pos'=>$request->pos,
//             'type'=>$request->type,
//             'adminid'=>Auth::guard('admin')->user()->adminid
//         ]);
//         return response()->json([
//             'status'=>true,
//         ]);

//     }
//     public function store(Request $request)
//     {
//         try {
//             if($request->data ==[])
//             {
//                 return response()->json([
//                     'status'=>true,
//                 ],202);
//             }
//             if($request->modele =='unDisplay')
//             {
//                 foreach ($request->data as $value) {
//                     $list = Product::Find($value);
//                     $list->display = 0;
//                     $list->save();
//                 }
//                 return response()->json([
//                     'mess'=>'undisplay',
//                     'status'=>true,
//                 ],200);
//             }
//             if($request->modele =='display')
//             {
//                 foreach ($request->data as $value) {
//                     $list = Product::Find($value);
//                     $list->display = 1;
//                     $list->save();
//                 }
//                 return response()->json([
//                     'mess'=>'display',
//                     'status'=>true,
//                 ],200);
//             }
           
//         } catch (\Exception $e) {
//             $errorMessage = $e->getMessage();
//             $response = [
//                 'status' => 'false',   
//                 'error' => $errorMessage
//             ];
//             return response()->json($response, 500);
//         }
//     }
   
//     public function index(Request $request)
//     {   
        
//         DB::table('adminlogs')->insert([
//             'adminid' => Auth::guard('admin')->user()->adminid,
//             'time' => Carbon::now(),
//             'ip'=> $request->ip(),
//             'action'=>'show',
//             'cat'=>'product',
//             'pid'=> $request->ip(),
//         ]);
//         $productPrice = $request->price;
//         $productPriceOld = $request->price_old;
//         $toDate=$request->toDate;
//         $fromDate=$request->fromDate;
//         $product = Product::with('productDesc','category','categoryDes','brand','brandDesc');
//         if (!empty($request->input('data'))&& $request->input('data') !== 'null'&& $request->input('data') !== 'undefined') {
//                 $product->whereHas('productDesc', function ($query) use ($request) {
//                     $query->where("title", 'like', '%' . $request->input('data') . '%')
//                     ->orWhere("macn", 'like', '%' . $request->input('data') . '%');
//                 });
//         }
//         if ($request->input('brand') !== null && $request->input('brand') !== '0') {
//             $product->where('brand_id', $request->input('brand'));
//         }
//         if ($request->input('category') !== null && $request->input('category') !== '0') {
//                 // $product->where(function ($query) use ($request) {
//                 //     $query->where('cat_id', $request->input('category'))
//                 //     ->orWhere('cat_list', 'like', '%' . $request->input('category') . ',%');
//                 // });
//             $product->whereRaw('FIND_IN_SET(?, cat_list)', [$request->input('category')]);   
//         }
//         if($request->status != '')
//         {
//             $product->where('stock',$request->status);
//         }
//         if(!empty($productPrice) && !empty($productPriceOld))
//         {

//             $product->whereBetween('price',[$productPrice,$productPriceOld]);
//         }
//         $toDate=Carbon::parse($toDate)->format('Y-m-d H:i:s');
//         $fromDate=Carbon::parse($fromDate)->format('Y-m-d H:i:s');
//         $products = $product->orderBy('product_id','desc')->paginate(15);
            
//         foreach($products as $product){
//             $product['productId']=substr(Crypt::encryptString($product->product_id),2);
//             $catIdParent=explode(",",$product->cat_list)[0];
//             $product['categoryParent']=CategoryDesc::where('cat_id', $catIdParent)->first();  
//         }
//         $brand = Brand::with('brandDesc')->get();
//         $category = Category::with('categoryDesc')->where('parentid',0)->get();
//         return response()->json([
//             'product' => $products, 
//             'brand' => $brand,
//             'category' => $category,
//         ]);            
//     }
//     public function destroy(Request $request,$id)
//     {
//         $list = Product::Find($id)->delete();
//         DB::table('adminlogs')->insert([
//             'adminid' => Auth::guard('admin')->user()->adminid,
//             'time' => Carbon::now(),
//             'ip'=> $request->ip(),
//             'action'=>'delete',
//             'cat'=>'product',
//             'pid'=> $request->ip(),
//         ]);
//         return response()->json([
//             'status'=>true
//         ]);
//     }
    
//     public function edit($id)
//     {
//         $list = Product::with('productDesc', 'category', 'categoryDes', 'brand', 'brandDesc', 'productPicture')->find($id);
//         $data = Price::with('propertiesProduct')->where('product_id',$id)->get();
//         $a=[];
//         foreach ($data as $key => $value) {
//             $tskt = [];
//             $valueTskt = [];
             
//             foreach ($value->propertiesProduct as $key => $item) {
//                 $tskt[] = $item->description;
//                 $valueTskt[] = $item->pv_id;
//             }
           
//             $listData = [
//                 'img' => $value->picture,
//                 'imgShow' => '',
//                 'si' => $value->price,
//                 'le' => $value->price_old,
//                 'tskt' => $tskt,
//                 'value' => $valueTskt,
//             ];
//             $a[] = $listData;
//         }
//         //return response()->json($a);
//         $list['arr'] = $a;
//         $list_cate = explode(',',$list->cat_list);
//         $i = count($list_cate);
//         for($j=0; $j<$i; $j++)
//         {
//             $save[] = (int)$list_cate[$j];
//         }
//         $list['list_cate']=$save;
//         $techs = $list->technology;
//                 $techs  = preg_replace_callback(
//                     '/(?<=^|\{|;)s:(\d+):\"(.*?)\";(?=[asbdiO]\:\d|N;|\}|$)/s',
//                     function($m){
//                         return 's:' . strlen($m[2]) . ':"' . $m[2] . '";';
//                     },
//                     $techs 
//                 );
//         $list['tech'] = unserialize($techs);
//         return response()->json([
//             'status'=> true,
//             'product' => $list
//         ] );
//     }
//     public function list($id)
//     {
//         $redis = Redis::connection();
//         if($redis){
//             $cachedProduct = Redis::get('product_'.$id);
//             if(isset($cachedProduct)){
//                 $Product = json_decode($cachedProduct, false);
//                 return response()->json([
//                     'status' => true,
//                     'message' => 'Fetched from redis',
//                     'data' => $Product,
//                 ]);
//             }else {
//                 $customTTL = 30; 
//                 $Product = Product::find($id);
//                 Redis::set('product_' . $id, $Product,'EX',$customTTL);
//                 return response()->json([
//                     'status' => false,
//                     'message' => 'Fetched from database',
//                     'data' => $Product,
//                 ]);
//             }
//         }else{
//             return response()->json([
//                 'status' => false,
//             ]);
//         }
        
//     }
//}