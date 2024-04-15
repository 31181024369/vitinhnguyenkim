<?php

namespace App\Http\Controllers\API\Member;

use Illuminate\Http\Request;
use App\Models\Promotion;
use App\Models\PromotionDesc;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PromotionController extends Controller
{
    
    public function index()
    {
       
       
        try {

            $promotion = Promotion::with('promotionDesc')->get();
            $response = [
                'status' => 'success',
                'list' => $promotion,

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
    public function detail(Request $request,$slug)
    {
        try{
           
            $promotionDesc = PromotionDesc::where('friendly_url',$slug)->first();
          
            return response()->json([
                'status' => true,
                'data'=> $promotionDesc,
                
            ]);
        }catch(Exception $e){
            return response()->json([
             'status' => false,
             'message' => $e->getMessage()
            ]);
        }
        
    }
    public function show(){
        $now = Carbon::now();
        $nowFormatted =$now->format('Y-m-d\TH:i:s.u\Z');
        
        $promotion=Promotion::with('promotionDesc')->get();
        $list=[];
        $list1=[];
        foreach ( $promotion as $value) {  //
            $timeStart= Carbon::createFromFormat("d/m/Y", $value->date_start_promotion);
            $timeEnd=Carbon::createFromFormat("d/m/Y",$value->date_end_promotion);
            $timeStartFormatted = $timeStart->format('Y-m-d\TH:i:s.u\Z');
            $timeEndFormatted = $timeEnd->format('Y-m-d\TH:i:s.u\Z');
           
            if ($timeStartFormatted > $nowFormatted ||  $timeEndFormatted < $nowFormatted) {
                $value->status=0;
                $value->save();  
            }
        }
        return response()->json([
            'status'=>true,
        ]);
    }
}
