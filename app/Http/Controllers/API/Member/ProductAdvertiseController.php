<?php

namespace App\Http\Controllers\API\Member;

use Carbon\Carbon;
use App\Models\ProductAdvertise;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\API\Member\AbstractController;

class ProductAdvertiseController extends Controller
{
    public function index()
    {
        try{

            $list = ProductAdvertise::get();
            $response = [
                'status' => true,
                'data' => $list
            ];
            return response()->json($response, 200);
        }catch(Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}