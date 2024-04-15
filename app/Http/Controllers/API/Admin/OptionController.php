<?php

namespace App\Http\Controllers\API\Admin;

use GuzzleHttp\Client;
use App\Models\Product;
use App\Models\Category;
use App\Models\CategoryDesc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\Admin\AbstractController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;

class OptionController extends AbstractController
{
    protected function getModel()
    {
           return new Category();
       }

       public function index()
       {
           // $a = Redis::get('a');
           // return $a
           try {
               $cachedProduct = Redis::get('list_');
               if (isset($cachedProduct)) {
                //    var_dump('vo');exit;
                   $finalData = json_decode($cachedProduct, false);
                   return response()->json([
                       'message' => 'Fetched from Redis',
                       'data' => $finalData,
                   ]);
               }
               else{
                //    var_dump('chuwa vo');exit;
               }
              
           } catch (\Exception $e) {
            //    var_dump('o day');exit;
               return response()->json([
                   'error' => $e->getMessage(),
               ]);
           }
       
       }
}