<?php 

namespace App\Http\Controllers\API\Admin;
use App\Models\Brand;
use App\Models\Product;
use App\Models\BrandDesc;
use App\Models\ProductDesc;
use App\Rules\BrandValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\Admin\AbstractController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class BrandController extends Controller
{
  
    protected function getModel()
    {
        return new Brand();
    }

    public function index(Request $request)
    {
        try {
            
            if($request->input('data')==""){
                $list = Brand::with('brandDesc','product','product.productDesc','product.categoryDes')->get();
            }
            else{
                $list = Brand::with('brandDesc','product','product.productDesc','product.categoryDes')
                ->whereHas('brandDesc', function ($query) use ($request) {$query->where("title", 'like', '%' . $request->input('data') . '%');})
                ->get();
            }
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
                    'catName' => isset($value->product->categoryDes) ? $value->product->categoryDes->cat_name : 'No Category Name' ,
                    'productId' => isset($value->product) ? $value->product->product_id : 'No product ID', 
                    'nameProduct' => isset($value->product->productDesc) ? $value->product->productDesc->title : 'No picture', 
                    'pictueProduct' => isset($value->product) ? $value->product->picture : 'No picture', 
                ];
            }
            $response = [
                'status' => true,
                'list' => $list, 
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

    public function store(Request $request)
    {
      
        $brand = new Brand();
        $brandDesc = new BrandDesc();
        
        try {
            $validator = BrandValidate::validate($request->all());
            if($validator->fails()){
                return response()->json([
                    'message'=>'Validations fails',
                    'errors'=>$validator->errors()
                ],422);
            }
            $brand->fill([
                'cat_id' => 0,
                'picture' =>"",
                'focus' => '0',
                'menu_order' => Brand::max('cat_id')+1,
                'views' => '1',
                'display' => '1',
                'date_post' => "0",
                'date_update' => "0",
                'adminid' => Auth::guard('admin')->user()->id,
            ])->save();
            
            $brandDesc->brand_id = $brand->brand_id;
            $brandDesc->title = $request->input('title');
            $brandDesc->description = $request->input('description');
            $brandDesc->friendly_url = $request->input('friendly_url');
            $brandDesc->friendly_title = $request->input('friendly_title');
            $brandDesc->metakey = $request->input('metakey');
            $brandDesc->metadesc = $request->input('metadesc');
            $brandDesc->lang ='vi';
            $brandDesc->save();
            $response = [
                'status' => 'success',
                'brand' => $brand,
                'brandDesc' => $brandDesc
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
    public function destroy($id)
    {
        $list = Brand::Find($id)->delete();
    }
    public function edit($id)
    {
        
        $listBrandDesc = Brand::with('brandDesc','product','product.productDesc')->find($id);
          return response()->json([
            'status'=> true,
            'brand' => $listBrandDesc
        ]);
    }

    
    public function update(Request $request, $id)
    {   
        $brand = new Brand();
        $brandDesc = new BrandDesc();
        $listBrand = Brand::Find($id);
        $validator = BrandValidate::validate($request->all());
            if($validator->fails()){
                return response()->json([
                    'message'=>'Validations fails',
                    'errors'=>$validator->errors()
                ],422);
            }
       
        $listBrand->cat_id = 0;
        $listBrand->picture = "";
        $listBrand->focus = '0';
        $listBrand->views = '1';
        $listBrand->display = '1';
        $listBrand->date_post = 0;
        $listBrand->date_update = 0;
        $listBrand->adminid = Auth::guard('admin')->user()->id;
        $listBrand->save();
        $brandDesc = BrandDesc::where('brand_id', $id)->first();
        if ($brandDesc) {
            $brandDesc->brand_id = $listBrand->brand_id;
            $brandDesc->title = $request->input('title');
            $brandDesc->description = $request->input('description');
            $brandDesc->friendly_url = $request->input('friendly_url');
            $brandDesc->friendly_title = $request->input('friendly_title');
            $brandDesc->metakey = $request->input('metakey');
            $brandDesc->metadesc = $request->input('metadesc');
            $brandDesc->lang ='vi';
            $brandDesc->save();
        }
    }
}
?>