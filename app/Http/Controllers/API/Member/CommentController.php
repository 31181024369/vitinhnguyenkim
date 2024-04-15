<?php

namespace App\Http\Controllers\API\Member;

use Carbon\Carbon;
use App\Models\Comment;
use App\Models\Product;
use App\Models\Member;
use App\Models\StatisticsPages;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Elasticsearch\ClientBuilder;
use App\Models\ProductDesc;
// Auth::guard('member')->user() ? Auth::guard('member')->user()->username : $data['d_name']
class CommentController extends Controller
{
    public function index(Request $request)
    {
      
       try{

            // $listComment = Comment::with('subcomments')->orderBy('comment_id','DESC')->where('parentid',0)->get();
            $listComment = Comment::orderBy('comment_id','DESC')->get();
            return response()->json([
                'listComment' => $listComment,
                'status' => true
            ]);
       }catch(Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
       }
    }
}
