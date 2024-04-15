<?php

namespace App\Http\Controllers\API\Member;

use GuzzleHttp\Client;
use App\Models\News;
use App\Models\NewsDesc;
use App\Models\NewsCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NewsCategoryController extends Controller
{
    public function index()
    {
        try{
            $newCategory = NewsCategory::with('newsCategoryDesc')
                    ->where('display', 1)
                    ->get();
             
            return response()->json([
                'status' => true,
                'data' => $newCategory,
            ]);
        }catch(Exception $e){
            return response()->json([
              'status' => false,
              'message' => $e->getMessage()
            ]);
        }
        
    }
}
