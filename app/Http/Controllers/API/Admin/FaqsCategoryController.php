<?php

namespace App\Http\Controllers\API\Admin;
use Carbon\Carbon;
use App\Models\FaqsCategory;
use App\Models\FaqsCategoryDesc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\Admin\AbstractController;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class FaqsCategoryController extends Controller
 {
    protected function getModel()
    {
           return new FaqsCategory();
       }
       public function index(Request $request)
    {
        try {
            if($request->data == 'undefined' || $request->data =="")
            {
                $faqsCategory = FaqsCategory::with('faqsCategoryDesc')->get();
            }
            else{
                $faqsCategory = FaqsCategory::with('faqsCategoryDesc')->whereHas('faqsCategoryDesc', function ($query) use ($request) {
                    $query->where("cat_name", 'like', '%' . $request->data . '%');
                })->get();
            }
            $response = [
                'status' => 'success',
                'list' => $faqsCategory,
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
        $faqsCategory = new FaqsCategory();
        $faqsCategoryDesc = new FaqsCategoryDesc();
        try {
            $cat = FaqsCategory::max('cat_id')+1;
            if($request->input('parentid')==0)
            {
             $cat_code = $cat;
            }
            else{
             $cat_code = $request->input('parentid').'_'.$cat;
            }
            $faqsCategory->fill([
                'cat_code' => $cat_code,
                'parentid'=> $request->input('parentid'),
                'picture' =>"",
                'is_default' => 0,
                'show_home' => 0,
                'focus_order' => 0,
                'menu_order' => 0,
                'views' => 0,
                'display' => $request->input('display'),
                'adminid' => Auth::guard('admin')->user()->adminid
            ])->save();
            $faqsCategoryDesc->cat_id = $faqsCategory->cat_id;
            $faqsCategoryDesc->cat_name = $request->input('cat_name');
            $faqsCategoryDesc->description = $request->input('description');
            $faqsCategoryDesc->friendly_url = $request->input('friendly_url');
            $faqsCategoryDesc->friendly_title = $request->input('friendly_title');
            $faqsCategoryDesc->metakey = $request->input('metakey');
            $faqsCategoryDesc->metadesc = $request->input('metadesc');
            $faqsCategoryDesc->lang = "vi";
            $faqsCategoryDesc->save();

            $response = [
                'status' => 'success',
                'faqsCategory' => $faqsCategory,
                'faqsCategoryDesc' => $faqsCategoryDesc,
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
        $list = FaqsCategory::Find($id)->delete();
    }
    public function edit($id)
    {
        $faqsCategory = FaqsCategory::with('faqsCategoryDesc')->find($id);
          return response()->json([
            'status'=> true,
            'faqsCategory' => $faqsCategory
        ]);
    }
    public function update(Request $request, $id)
    {   
        $faqsCategory = new FaqsCategory();
        $faqsCategoryDesc = new FaqsCategoryDesc();
        $listFaqsCategory = FaqsCategory::Find($id);
        
            if($request->input('parentid')==0)
            {
             $cat_code = $listFaqsCategory->cat_id;
            }
            else{
             $cat_code = $request->input('parentid').'_'.$listFaqsCategory->cat_id;
            }
        $listFaqsCategory->fill([
            'cat_code' => $cat_code,
            'parentid'=> $request->input('parentid'),
            'display' => $request->input('display'),
            'adminid' => Auth::guard('admin')->user()->adminid
        ])->save();

        $faqsCategoryDesc = FaqsCategoryDesc::where('cat_id', $id)->first();
        if ($faqsCategoryDesc) {
            $faqsCategoryDesc->cat_name = $request->input('cat_name');
            $faqsCategoryDesc->description = $request->input('description');
            $faqsCategoryDesc->friendly_url = $request->input('friendly_url');
            $faqsCategoryDesc->friendly_title = $request->input('friendly_title');
            $faqsCategoryDesc->metakey = $request->input('metakey');
            $faqsCategoryDesc->metadesc = $request->input('metadesc');
            $faqsCategoryDesc->lang = "vi";
            $faqsCategoryDesc->save();
        }
        $response = [
            'status' => 'true',
        ];
        return response()->json($response, 200);
    }
 }