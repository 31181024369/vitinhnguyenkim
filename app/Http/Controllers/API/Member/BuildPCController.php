<?php

namespace App\Http\Controllers\API\Member;

use PDF;
use App\Models\Brand;
use GuzzleHttp\Client;
use App\Models\Product;
use App\Models\Category;
use App\Models\BrandDesc;

use Illuminate\Http\Request;
use App\Exports\BuildPCExport;
use App\Models\ProductCatOption;
use App\Http\Controllers\Controller;
use App\Models\ProductCatOptionDesc;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BuildPCController extends Controller
{
    /**Input:Key,title 
        Ouput: result search product buildings list from database table with search criteria parameters 
    */
    public function index(Request $request)
    {
        try{
        if($request->has('key')){
            $catId = $request->key;
            //return $catId;
            $brandId = $request->brandId;
            $opSearch = $request->opSearch;
            $price = $request->price ? $request->price : 'DESC';
            $products = Product::query()
                            ->with('productDesc','category.categoryDesc','priceList')->where('cat_id', $catId)->where('stock',1);
            //return $products->get();
                    if(!empty($price)) {
                        $products->orderBy('price', $price);
                    }         
                    if(!empty($opSearch)) {
                        $products->whereRaw('FIND_IN_SET(?,op_search)', [$opSearch]);
                    }  
                    if(!empty($brandId)) {
                        $products->where('brand_id', $brandId);
                    }  
                    if(!empty($opSearch)) {
                        $products->whereRaw('FIND_IN_SET(?,op_search)', [$opSearch]);
                    }  
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
                $listProduct = $products->paginate(20);      
                foreach ($listProduct as $product) {
                    $brandName = BrandDesc::where('brand_id',$product->brand_id)->first()->title;
                    $catNameParent = Category::with('categoryDesc')->where('cat_id',$product->category->parentid)->first();
                    if(!empty($listItemImg[$product->product_id])){
                        $product->picture = $listItemImg[$product->product_id];
                    }
                                $price = $product->price;
                                $price_old = $product->price_old;
                                
                            if(count($product->priceList)>0){
                                
                                foreach ($product->priceList as $key => $row) {
                                    
                                    if($row->main == 1)
                                    {
                                        $price = $row ->price;
                                        $price_old = $row ->price_old;
                                    }
                                }
                                
                            }

                            $encry =  Crypt::encryptString($product->product_id);
                            $encryKey = substr($encry, 2);
                        $data[] = [
                            'productName' => $product->productDesc->title ?? null,
                            'price' => $price ?? null,
                            'catId' => $product->cat_id ?? null,
                            'priceOld' => $price_old ?? null,
                            'picture' => $product->picture ?? null,
                            'friendLyUrl' => $product->productDesc->friendly_url ?? null,
                            'product_id'=>$encryKey,
                            'cat_name' => $product->category,
                            'cat_name_parrent' =>$catNameParent,
                            'macn' => $product->macn,
                            'stock' => $product->stock,
                            'brand_name' =>$brandName,
                            'metakey'=> $product->productDesc->metakey ?? null,
                            'metadesc'=> $product->productDesc->metadesc ?? null
                        ];
                   
                }
                
                return response()->json([
                    'productResult' => $data ?? null,
                    'status' => true
                ]);
            
        }else{
            // 11 in database category_table : linh kien
            $category = Category::where('parentid',11)->pluck('cat_id');
            $listProduct = Product::with('productDesc','category','category.categoryDesc','category.subCategories')
                            ->whereIn('cat_id',$category)->get();
                            // return $listProduct;
            foreach($listProduct as $key => $catProduct)
            {
                    $data[] = [
                        'catId' => $catProduct->cat_id,
                        'catList' => $catProduct->cat_list,
                        'catName' => $catProduct->category->categoryDesc->cat_name,
                        'picture' => $catProduct->picture,
                        'price' => $catProduct->price ?? null,
                        'priceOld' => $catProduct->price_old ?? null,
                        'productId' => Crypt::encryptString($catProduct->product_id),
                        'productName' => $catProduct->productDesc->title??null,
                        'friendlyName' => $catProduct->productDesc->friendly_url??null,
                        'friendlyTitle' => $catProduct->productDesc->friendly_title??null,
                        'picture' => $catProduct->picture ?? null,
                        'parentId' => $catProduct->category->parentid,
                        'metakey'=>$catProduct->productDesc->metakey ?? null,
                        'metadesc'=>$catProduct->productDesc->metadesc ?? null,
                    ];
            }
            $groupData = [];
                foreach ($data as $item) {
                    $parentId = $item['catId'];
                    if (!isset($groupData[$parentId])) {
                        $groupData[$parentId] = [
                            'catId' => $parentId,
                            'catList' => $item['catList'],
                            'catName' => $item['catName'],
                            'picture' => $item['picture'],
                            'productId' => $item['productId'],
                            'productName' => $item['productName'],
                            'friendlyName' => $item['friendlyName'],
                            'friendlyTitle' => $item['friendlyTitle'],
                            'price' => $item['price'],
                            'priceOld' => $item['priceOld'],
                            'picture' => $item['picture'] ?? null,
                            'parentId' => '1',
                            'metakey'=>$item['metakey'] ?? null,
                            'metadesc'=>$item['metadesc'] ?? null,
                            'productParent' => []
                        ];
                    }
                    $groupData[$parentId]['productParent'][] = [
                            'catList' => $item['catList'],
                            'catName' => $item['catName'],
                            'productName' => $item['productName'],
                            'friendlyName' => $item['friendlyName'],
                            'friendlyTitle' => $item['friendlyTitle'],
                            'price' => $item['price'],
                            'priceOld' => $item['priceOld'],
                            'picture' => $item['picture'] ?? null,
                            'metakey'=>$item['metakey'] ?? null,
                            'metadesc'=>$item['metadesc'] ?? null,
                            'parentId' => '2'
                        ];
                }
            $rearrangedData = array_values($groupData);
            return response()->json(['data'=>$rearrangedData,'status'=>'true']);
        }
        }catch(Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
        
        
    }
    
    
    public function exportExcelPC(Request $request)
    {
        try{

            $dataKey = json_decode($request['key']);
            //return $data;
            $data=[];
            foreach($dataKey as $item){
                $data[]=[
                    'productName'=>$item->productName,
                    'quantity'=>$item->quantity,
                    'price'=>$item->price,
                    'time'=>Carbon::now('Asia/Ho_Chi_Minh')
                ];
            }
            $fileName = 'build-pc.xlsx';
            $export = new BuildPCExport($data);
            $fileContents = Excel::raw($export, \Maatwebsite\Excel\Excel::XLSX);
        
            $headers = [
                'Content-Type' => 'application/vnd.ms-excel',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"'
            ];
        
            return response($fileContents, 200, $headers);
        }catch(Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }


    public function downloadPDF(Request $request)
    { 
        try{
        $data = $request->all();
        //return  $data;
       
        $pdf = PDF::loadView('pdf-template', compact('data'));
        return $pdf->download('buildpc.pdf');
        }
        catch(Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function filterBuildPc(Request $request)
    {
    try {
        $catId = $request->key; //209
        $opSearch = Product::where('cat_id', $catId)->pluck('op_search')->filter()->toArray();
        $brand = Product::with('brandDesc')->where('cat_id', $catId)->get();
      
        $out = [];
        foreach ($opSearch as $item) {
            $sub = explode(',', $item);
            $out = array_merge($out, $sub);
        }
        $listOpsearch = ProductCatOptionDesc::whereIn('op_id', $out)->get();
        $itemData = [];
        $brandId=[];
        foreach($brand as $item){
            if(!in_array($item->brand_id,$brandId))
            {
                $brandId[]=$item->brand_id;
                $dataBrand[]=[
                    'brandName' => $item->brandDesc->title,
                    'brandId' => $item->brand_id
                ];
            }
        }
     
        $dataOpsearch = $listOpsearch->map(function ($val) {
            return [
                'OpSearchName' => $val->title,
                'Op_id' => $val->op_id
            ];
        });

        return response()->json([
            'brand' => $dataBrand,
            'opSearch' => $dataOpsearch,
        ]);
    } catch (Exception $e) {
        return response()->json([
            'status' => false,
            'error' => $e->getMessage(),
        ]);
    }
}
    
}
