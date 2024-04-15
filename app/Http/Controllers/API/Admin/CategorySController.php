
<?php


use App\Models\Product;
use App\Models\Category;
use App\Models\ProductDesc;
use App\Models\CategoryDesc;
use Illuminate\Http\Request;
use App\Rules\CategoryValidate;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\Admin\AbstractController;
use App\Models\ProductCatOption;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;

class CategoryController extends Controller
{

    protected function getModel()
    {
        return new Category();
    }
    public function index(Request $request)
    {
        if(empty($request->input('data'))||$request->input('data')=='undefined' ||$request->input('data')=='')
        {
        $categories = Category::with('subCategories','categoryDesc','catOption','catOption.catOptionDesc'
                          ,'subCategories.categoryDesc')
                          ->where('parentid','cat_id')
                          ->get();
        }
        else{
            $categories = Category::with('subCategories','categoryDesc','catOption','catOption.catOptionDesc'
            ,'subCategories.categoryDesc')
            ->whereHas('categoryDesc', function ($query) use ($request) {
                $query->where("cat_name", 'like', '%' . $request->input('data') . '%');})
            ->get();
        }                  
        return response()->json($categories);
    }

    public function store(Request $request)
    {

        $category = new Category();
        $categoryDesc = new CategoryDesc();
        try {
            $validator = CategoryValidate::validate($request->all());
            if($validator->fails()){
                return response()->json([
                    'message'=>'Validations fails',
                    'errors'=>$validator->errors()
                ],422);
            }
            $numberParentid = preg_replace('/[^0-9]/', '', $request->input('parentid'));

            $cat = Category::max('cat_id')+1;
           if($numberParentid==0)
           {
            $cat_code = $cat;
           }
           else{
            $cat_code = $numberParentid.'_'.$cat;
           }
            $category->fill([
                'cat_code' =>  $cat_code,
                'parentid' => $numberParentid,
                'color' => $request->input('color'),
                'psid' => '1',
                'is_default' => '1',
                'is_buildpc' => '1',
                'show_home' =>'0',
                'list_brand' => implode(',',$request->input('list_brand')),
                'list_price' => serialize($request->input('list_price')),
                'list_support' => implode(',',$request->input('list_support')),
                'menu_order' => '1',
                'views' => '0',
                'display' => $request->input('display'),
                'date_post' => $request->input('date_post'),
                'date_update' => $request->input('date_update'),
                'adminid' => Auth::guard('admin')->user()->adminid,
            ])->save();
            
            $categoryDesc->cat_id = $category->cat_id;
            $categoryDesc->cat_name = $request->input('cat_name');
            $categoryDesc->home_title = $request->input('home_title');
            $categoryDesc->description = $request->input('description');
            $categoryDesc->friendly_url = $request->input('friendly_url');
            $categoryDesc->friendly_title = $request->input('friendly_title');
            $categoryDesc->metakey = $request->input('metakey');
            $categoryDesc->metadesc = $request->input('metadesc');
            $categoryDesc->lang ='vi';
            $categoryDesc->script_code = $request->input('script_code');
            $categoryDesc->save();
            $response = [
                'status' => 'success',
                'category' => $category,
                'categoryDesc' => $categoryDesc
            ];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            $response = [
                'status' => 'false',   
                'error' => $errorMessage,
                
            ];
            return response()->json($response, 500);
        }
    }

    public function destroy($id)
    {
        $list = Category::Find($id)->delete();
    }

    public function edit($id)
    {
        $listCategory = Category::with('categoryDesc','product.productDesc')->find($id);
        $row = [];
        foreach(explode(",",$listCategory->list_brand) as $item)
        {
            $row[] = (int)$item;
        }
        $listCategory['brand'] =$row;
            return response()->json([
                'status'=> true,
                'category' => $listCategory
            ]);
        }

    
    public function show(Request $request)
    {   
        if(empty($request->input('data'))||$request->input('data')=='undefined' ||$request->input('data')=='')
        {
            $categories = Category::with('categoryDesc')->get();
        }
        else
        {
            $categories = Category::with('categoryDesc')->whereHas('categoryDesc', function ($query) use ($request) {
                $query->where("cat_name", 'like', '%' . $request->input('data') . '%');})
            ->get();
        }
        return response()->json($categories);
    }

}