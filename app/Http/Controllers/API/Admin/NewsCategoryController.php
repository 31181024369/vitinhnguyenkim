<?php

namespace App\Http\Controllers\API\Admin;
use Carbon\Carbon;
use App\Models\NewsCategory;
use App\Models\NewsCategoryDesc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\AbstractController;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;


class NewsCategoryController extends Controller
 {
    protected function getModel()
    {
           return new NewsCategory();
       }

    public function index(Request $request)
    { 
        try {
            if($request->data == 'undefined' || $request->data =="")
            {
            $newsCategory = NewsCategory::with('newsCategoryDesc')->get();
            }
            else
            {
            $newsCategory = NewsCategory::with('newsCategoryDesc')->whereHas('newsCategoryDesc', function ($query) use ($request) {
                $query->where("cat_name", 'like', '%' . $request->data . '%');
            })->get();
            }
            $response = [
                'status' => 'success',
                'list' => $newsCategory,
            ];
            return response()->json( $response, 200 );
        } catch ( \Exception $e ) {
            $errorMessage = $e->getMessage();
            $response = [
                'status' => 'false',
                'error' => $errorMessage
            ];

            return response()->json( $response, 500 );
        } 
    }
    public function store(Request $request)
    {
        $newsCategory = new NewsCategory();
        $newsCategoryDesc = new NewsCategoryDesc();
        
        try {
          
            $newsCategory->fill([
                'cat_code' => NewsCategory::max('cat_id')+1,
                'parentid'=> 0,
                'picture' => "",
                'is_default' => 0,
                'show_home' => 0,
                'focus_order' => 0,
                'menu_order' => 0,
                'views' => 0,
                'display' => $request->input('display'),
                'adminid' => 1,
            ])->save();
            $newsCategoryDesc->cat_id = $newsCategory->cat_id;
            $newsCategoryDesc->cat_name = $request->input('cat_name');
            $newsCategoryDesc->description = $request->input('description');
            $newsCategoryDesc->friendly_url = $request->input('friendly_url');
            $newsCategoryDesc->friendly_title = $request->input('friendly_title');
            $newsCategoryDesc->metakey = $request->input('metakey');
            $newsCategoryDesc->metadesc = $request->input('metadesc');
            $newsCategoryDesc->lang = "vi";
            $newsCategoryDesc->save();

            $response = [
                'status' => 'success',
                'newsCategory' => $newsCategory,
                'newsCategoryDesc' => $newsCategoryDesc,
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
        $list = NewsCategory::Find($id)->delete();
    }
    public function edit($id)
    {
        $newsCategory = NewsCategory::with('newsCategoryDesc')->find($id);
          return response()->json([
            'status'=> true,
            'newsCategory' => $newsCategory
        ]);
    }
    public function update(Request $request, $id)
    {   

        $newsCategory = new NewsCategory();
        $newsCategoryDesc = new NewsCategoryDesc();
        $listNewsCategory = NewsCategory::Find($id);
        
        $listNewsCategory->cat_code = $listNewsCategory->cat_code;
        $listNewsCategory->parentid = 0;
        $listNewsCategory->picture = "";
        $listNewsCategory->is_default = 0;
        $listNewsCategory->show_home = 0;
        $listNewsCategory->focus_order = 0;
        $listNewsCategory->menu_order = 0;
        $listNewsCategory->views = $listNewsCategory->views;
        $listNewsCategory->display = $request->input('display');
        $listNewsCategory->adminid = 1;
        $listNewsCategory->save();

        $newsCategoryDesc = NewsCategoryDesc::where('cat_id', $id)->first();
        if ($newsCategoryDesc) {
            $newsCategoryDesc->cat_name = $request->input('cat_name');
            $newsCategoryDesc->description = $request->input('description');
            $newsCategoryDesc->friendly_url = $request->input('friendly_url');
            $newsCategoryDesc->friendly_title = $request->input('friendly_title');
            $newsCategoryDesc->metakey = $request->input('metakey');
            $newsCategoryDesc->metadesc = $request->input('metadesc');
            $newsCategoryDesc->lang = "vi";
            $newsCategoryDesc->save();
        }
    }
}