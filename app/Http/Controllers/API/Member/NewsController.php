<?php

namespace App\Http\Controllers\API\Member;

use GuzzleHttp\Client;
use App\Models\News;
use App\Models\NewsDesc;
use App\Models\NewsCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class NewsController extends Controller
{
    public function index($slug)
    {
       
        try{
            $category = NewsCategory::with('newsCategoryDesc')
            ->whereHas('newsCategoryDesc', function ($query) use ($slug) {$query->where('friendly_url',$slug);})
            ->where('display', 1)
            ->first()->cat_id;
          
           
        //    $listData = News::with('newsDesc')->where('cat_id',$category)->where('display', 1)->orderBy('news_id','DESC')->paginate(6);
            $listData=DB::table('news')
            ->where('cat_id',$category)
            ->where('display', 1)
            ->join('news_desc', 'news_desc.news_id', '=', 'news.news_id')
             ->select('news.*','news_desc.title','news_desc.short','news_desc.friendly_url','news_desc.metakey','news_desc.metadesc')
          
            ->orderBy('news_id','DESC')
            ->paginate(6);
            //return  $listData;
          
                
            return response()->json([
                'status' => true,
                'data' => $listData,
            ]);
        }catch(Exception $e){
            return response()->json([
              'status' => false,
              'message' => $e->getMessage()
            ]);
        }
        
    }
    public function search(Request $request)
    {
        
       try{

            if(isset($_GET['search'])){
                $search=$_GET['search'];
                $listNews = NewsDesc::with('news')->where('title', 'LIKE', '%'.$search.'%')->get();
                return response()->json($listNews);
            }else{
                return response()->json([
                    'message' => 'Invalid search parameters  provided for this search term.',
                    'status' => true
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
    
    public function detail(Request $request,$slug)
    {
        try{
            
            $newsDesc = NewsDesc::with('comment.subcomments')->where('friendly_url',$slug)->first();
            //$newsRelates = NewsDesc::whereNotIn('id',[$newsDesc->id])->get();
            return response()->json([
                'status' => true,
                'data'=> $newsDesc,
                // 'newsRelates' => $newsRelates
            ]);
        }catch(Exception $e){
            return response()->json([
             'status' => false,
             'message' => $e->getMessage()
            ]);
        }
        
    }
}
