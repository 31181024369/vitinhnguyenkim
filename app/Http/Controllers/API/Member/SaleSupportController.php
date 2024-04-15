<?php

namespace App\Http\Controllers\API\Member;

use Carbon\Carbon;
use App\Models\Member;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SaleSupportController extends Controller
{
    public function index()
    {
        try{

        $sale = "";
        
        if(Auth::guard('member')->check()){
            $sale = Admin::where('adminid', Auth::guard('member')->user()->company)
               ->get();
            $response = [
                'status' => 'success',
                'list' => $sale,

            ];

            return response()->json( $response, 200 );
         }
        }catch(Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}