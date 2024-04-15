<?php

namespace App\Http\Controllers\API\Admin;

use Carbon\Carbon;
use App\Models\News;
use App\Models\NewsDesc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\AbstractController;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;

class NewsController extends Controller
 {
    protected function getModel()
    {
           return new News();
       }

    public function index(Request $request)
    {
       
        try {
           
            $category=$request['catagory'];
           
            $query=News::with('newsDesc','categoryDesc');
            if($request->data == 'undefined' || $request->data =="")
            {
                $list = $query;
            }
            else{
                $list = $query->whereHas('newsDesc', function ($query) use ($request) {
                    $query->where("title", 'like', '%' . $request->data . '%');
                });
            }
            if(isset($category)){
                $list=$query->where('cat_id',$category);
            }

            $news=$list->paginate(10);
            $response = [
                'status' => 'success',
                'list' => $news,
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
        $news = new News();
        $newsDesc = new NewsDesc();
        try {
        
            $filenameWithExt = $request->file('picture')->getClientOriginalName();
        
           
            //Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            
            // Get just ext
            $extension = $request->file('picture')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            // Upload Image
            
            $filePath = $request->file('picture')->storeAs('news',$fileNameToStore);
          
            $news->fill([
                'cat_id' => $request->input('cat_id'),
                'cat_list'=> $request->input('cat_list'),
                'picture' => $filePath,
                'focus' => $request->input('focus'),
                'focus_order' => $request->input('focus_order'),
                'views' => $request->input('views'),
                'display' => $request->input('display'),
                'menu_order' => $request->input('menu_order'),
                'adminid' => $request->input('adminid')
            ])->save();
            $newsDesc->news_id = $news->news_id;
            $newsDesc->product_id = $request->input('product_id');
            $newsDesc->title = $request->input('title');
            $newsDesc->description = $request->input('description');
            $newsDesc->short = $request->input('short');
            $newsDesc->friendly_url = $request->input('friendly_url');
            $newsDesc->friendly_title = $request->input('friendly_title');
            $newsDesc->metakey = $request->input('metakey');
            $newsDesc->metadesc = $request->input('metadesc');
            $newsDesc->lang = $request->input('lang');
            $newsDesc->save();

            $response = [
                'status' => 'success',
                'news' => $news,
                'newsDesc' => $newsDesc,
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
        $list = News::Find($id)->delete();
    }
    public function edit($id)
    {
        $news = News::with('newsDesc')->find($id);
        $list_cate = explode(',',$news->cat_list);
        $i = count($list_cate);
        for($j=0; $j<$i; $j++)
        {
            $save[] = (int)$list_cate[$j];
        }
        $news['list_cate']=$save;
 
        $list_product = explode(',',$news->newsDesc->product_id);

        for($j=0; $j<count($list_product); $j++)
        {
            $product[] = (int)$list_product[$j];
        }
        $news['product']=$product;
          return response()->json([
            'status'=> true,
            'news' => $news
        ]);
    }
    public function update(Request $request, $id)
    {   
        $news = new News();
        $newsDesc = new NewsDesc();
        $listNews = News::Find($id);
        
        if ($request->hasFile('picture')) {
            $filenameWithExt = $request->file('picture')->getClientOriginalName();
           
            //Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            
            // Get just ext
            $extension = $request->file('picture')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            // Upload Image
            
            $filePath = $request->file('picture')->storeAs('guide',$fileNameToStore);
        } else {   
            $filePath = $list->picture; 
        }
        
        $listNews->cat_id = $request->input('cat_id');
        $listNews->cat_list = $request->input('cat_list');
        $listNews->picture = $filePath;
        $listNews->focus = $request->input('focus');
        $listNews->focus_order = $request->input('focus_order');
        $listNews->views = $request->input('views');
        $listNews->display = $request->input('display');
        $listNews->menu_order = $request->input('menu_order');
        $listNews->adminid = $request->input('adminid');
        $listNews->save();

        $newsDesc = NewsDesc::where('news_id', $id)->first();
        if ($newsDesc) {
            $newsDesc->product_id = $request->input('product_id');
            $newsDesc->title = $request->input('title');
            $newsDesc->description = $request->input('description');
            $newsDesc->short = $request->input('short');
            $newsDesc->friendly_url = $request->input('friendly_url');
            $newsDesc->friendly_title = $request->input('friendly_title');
            $newsDesc->metakey = $request->input('metakey');
            $newsDesc->metadesc = $request->input('metadesc');
            $newsDesc->lang = $request->input('lang');
            $newsDesc->save();
        }
    }
 }