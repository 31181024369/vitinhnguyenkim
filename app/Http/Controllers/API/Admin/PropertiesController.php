<?php 

namespace App\Http\Controllers\API\Admin;
use App\Models\Properties;
use App\Models\PropertiesValue;
use App\Models\PropertiesCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryDesc;
use App\Models\PropertiesChildCate;
class PropertiesController extends Controller
{
  
    protected function getModel()
    {
        return new Properties();
    }

    public function index(Request $request)
    {
        try {
            if($request->data == 'undefined' || $request->data =="")
            {
                $listProperties = Properties::with('propertiesValue')->get();
            }
            else{
                $listProperties = Properties::with('propertiesValue')->where("title", 'like', '%' . $request->data . '%')->get();
            }
            $response = [
                'status' => true,
                'list' => $listProperties, 
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
    public function checkOpCategory($cat_id,$title){
        try{
            $propertiesCategory = PropertiesCategory::with('properties')->where('cat_id',$cat_id)->get();
            // return  $propertiesCategory;
            $value=false;
            foreach($propertiesCategory as $items){
                if(isset($items->properties) && $items->properties->title==$title){
                    return 1;
                }
            }
            return 0;
            
           
        }catch(\Exception $e){
            $errorMessage = $e->getMessage();
            $response = [
                'status' => 'false',   
                'error' => $errorMessage
            ];
            return response()->json($response, 500);
        }
    }
    public function selectClildCategory($cat_id)
    {
        try{
            $category=Category::with('categoryDesc','catChildProperties.properties.propertiesValue')->where('parentid',$cat_id)->get();
            return  response()->json([
                'status'=>true,
                'data'=>$category
            ]);
        }catch(\Exception $e){
            $errorMessage = $e->getMessage();
            $response = [
                'status' => 'false',   
                'error' => $errorMessage
            ];
            return response()->json($response, 500);
        }
    }
    public function selectOneClildCategory($id)
    {
        try{

            $categories = Category::with('subCategories.catProperties.properties.propertiesValue','categoryDesc','catOption','catOption.catOptionDesc'
            ,'subCategories.categoryDesc','catProperties.properties.propertiesValue')
           
            ->get();
            return  $categories;
            
            $category=Category::with('categoryDesc','catChildProperties.properties.propertiesValue')->where('cat_id',$id)->first();
            return  response()->json([
                'status'=>true,
                'data'=>$category
            ]);
        }catch(\Exception $e){
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
       
        $properties = new Properties();
        try {
            if($request->input('title') == '')
            {
                if($request->value != '')
                {
                    foreach ($request->value as $item) {
                        $propertiesValue = new PropertiesValue();
                        $propertiesValue->properties_id = $request->update;
                        $propertiesValue->name = $item;
                        $propertiesValue->save();
                    }
                }   
                $response = [
                    'status' => 'true',
                    //'data' => $properties,
                ];
                return response()->json($response, 200);
            }
            else
            {
               
                $properties->title = $request->input('title');
                $properties->save();
                if($request->cat_id !=[])
                {
                    foreach ($request->cat_id as $items) {
                        $cate=Category::where('cat_id',$items)->first();
                        $propertiesCategory = new PropertiesCategory();
                        $propertiesCategory->cat_id = $items;
                        $propertiesCategory->properties_id = $properties->id;
                        if($cate->parentid!=0)
                        {
                            $propertiesCategory->parentid =  $cate->parentid;
                        }
                        $propertiesCategory->save();
                        
                    }
                }
                if($request->value != '')
                {
                    foreach ($request->value as $item) {
                        $propertiesValue = new PropertiesValue();
                        $propertiesValue->properties_id = $properties->id;
                        $propertiesValue->name = $item;
                        $propertiesValue->save();
                    }
                }   
                $response = [
                    'status' => 'true',
                    'data' => $properties,
                ];
                return response()->json($response, 200);
            }
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
        $properties = Properties::Find($id)->delete();
        $propertiesValue = PropertiesValue::where('properties_id',$id)->delete();
        $propertiesCategory = PropertiesCategory::where('properties_id',$id)->delete();
        $propertiesChildCategory =PropertiesChildCate::where('properties_id',$id)->delete();
    }
    public function edit($id)
    {
        $listProperties = Properties::find($id);
        $listProperties['cat_id'] = PropertiesCategory::where('properties_id',$id)->pluck('cat_id');
        $listProperties['propertiesValue'] = PropertiesValue::where('properties_id', $id)->pluck('name');
        $response = [
            'status' => 'true',   
            'list' => $listProperties
        ];
        return response()->json($response, 200);
    }

    
    public function update(Request $request, $id)
    {
        $properties = Properties::find($id);
        try {
            $properties->title = $request->input('title');
            $properties->save();

            $listPropertiesCategory = PropertiesCategory::where('properties_id',$id)->delete();
            if($request->cat_id !=[])
            {
                foreach ($request->cat_id as $items) {
                    $propertiesCategory = new PropertiesCategory();
                    $propertiesCategory->cat_id = $items;
                    $propertiesCategory->properties_id = $properties->id;
                    $cate=Category::where('cat_id',$items)->first();
                    if($cate->parentid!=0)
                    {
                        $propertiesCategory->parentid =  $cate->parentid;
                    }
                    $propertiesCategory->save();
                }
            }
            
            $listPropertiesValue = PropertiesValue::where('properties_id',$id)->delete();
            if($request->value != '')
            {
                foreach ($request->value as $item) {
                    $propertiesValue = new PropertiesValue();
                    $propertiesValue->properties_id = $id;
                    $propertiesValue->name = $item;
                    $propertiesValue->save();
                }
            }   
            

            $response = [
                'status' => 'true',
                'data' => $properties,
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
?>