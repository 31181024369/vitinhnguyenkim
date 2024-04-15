<?php

namespace App\Http\Controllers\API\Member;

use GuzzleHttp\Client;
use App\Models\Star;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;

class StarController extends Controller
{
    public function index(Request $request)
    {
        try {
            $product_id = Crypt::decryptString('ey'.$request->input('product_id'));
            if($request->star == 'undefined' || $request->star =="")
            {
                $star = Star::with('product_id',$product_id)->get();
            }
            else
            {
                $star = Star::with('product_id',$product_id)->where('star',$request->star)->get();
            }
            $sumStar = count($star);
            $response = [
                'status' => 'success',
                'list' => $star,
                'sum' => $sumStar,
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
        $product_id = Crypt::decryptString('ey'.$request->input('product_id'));
        Star::where('product_id',$product_id)
        ->where('mem_id',Auth::guard('member')->user()->mem_id)
        ->delete();

        $star = new Star();
        try {
            $star->product_id = $product_id;
            $star->mem_id = Auth::guard('member')->user()->mem_id;
            $star->star = $request->input('star');
            $star->save();

            $response = [
                'status' => 'true',
                'star' => $star,
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
