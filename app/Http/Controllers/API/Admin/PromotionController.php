<?php

namespace App\Http\Controllers\API\Admin;
use Carbon\Carbon;
use App\Models\Promotion;
use App\Models\PromotionDesc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
// use App\Http\Controllers\API\AbstractController;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;


class PromotionController extends Controller
 {
    protected function getModel()
    {
           return new Promotion();
    }

    public function search(Request $request)
    {
       
        if(isset($_GET['search'])){
            $search = $_GET['search'];
            $listPromotion = PromotionDesc::with('promotion')->where('title', 'LIKE', '%'.$search.'%')->get();
            return response()->json($listPromotion);
        }else{
            return response()->json([
                'message' => 'Invalid search parameters  provided for this search term.',
                'status' => true
            ]);
        }

    }

       public function index(Request $request)
        {
        try {
            if($request->data == 'undefined' || $request->data =="")
            {
                $promotion = Promotion::with('promotionDesc')->paginate(10);
            }
            else{
                $promotion = Promotion::with('promotionDesc')->whereHas('promotionDesc', function ($query) use ($request) {
                $query->where("title", 'like', '%' . $request->data . '%');})->paginate(10);
            }
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
    public function store(Request $request)
    {
        $promotion = new Promotion();
        $promotionDesc = new PromotionDesc();
        try {
        
            $filenameWithExt = $request->file('picture')->getClientOriginalName();
           
            //Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            
            // Get just ext
            $extension = $request->file('picture')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            // Upload Image
            
            $filePath = $request->file('picture')->storeAs('promotion',$fileNameToStore);
          
            $promotion->fill([
                'picture' => $filePath,
                'focus'=> $request->input('focus'),
                'focus_order' => $request->input('focus_order'),
                'views' => $request->input('views'),
                'display' => $request->input('display'),
                'menu_order' => $request->input('menu_order'),
                'adminid' => $request->input('adminid'),
                'date_start_promotion' => $request->input('date_start_promotion'),
                'date_end_promotion' => $request->input('date_end_promotion')
            ])->save();
            $promotionDesc->promotion_id = $promotion->promotion_id;
            $promotionDesc->title = $request->input('title');
            $promotionDesc->description = $request->input('description');
            $promotionDesc->short = $request->input('short');
            $promotionDesc->friendly_url = $request->input('friendly_url');
            $promotionDesc->friendly_title = $request->input('friendly_title');
            $promotionDesc->metakey = $request->input('metakey');
            $promotionDesc->metadesc = $request->input('metadesc');
            $promotionDesc->lang = $request->input('lang');
            $promotionDesc->save();

          
            $response = [
                'status' => 'success',
                'promotion' => $promotion,
                'promotionDesc' => $promotionDesc,
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
        $list = Promotion::Find($id)->delete();
    }
    public function edit($id)
    {
        $promotion = Promotion::with('promotionDesc')->find($id);
          return response()->json([
            'status'=> true,
            'promotion' => $promotion
        ]);
    }
    public function update(Request $request, $id)
    {   
        $promotion = new Promotion();
        $promotionDesc = new PromotionDesc();
        $listPromotion = Promotion::Find($id);
        
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
        
        $listPromotion->fill([
            'picture' => $filePath,
            'focus'=> $request->input('focus'),
            'focus_order' => $request->input('focus_order'),
            'views' => $request->input('views'),
            'display' => $request->input('display'),
            'menu_order' => $request->input('menu_order'),
            'adminid' => $request->input('adminid'),
            'date_start_promotion' => $request->input('date_start_promotion'),
            'date_end_promotion' => $request->input('date_end_promotion')
        ])->save();

        $promotionDesc = PromotionDesc::where('promotion_id', $id)->first();
        if ($promotionDesc) {
            $promotionDesc->title = $request->input('title');
            $promotionDesc->description = $request->input('description');
            $promotionDesc->short = $request->input('short');
            $promotionDesc->friendly_url = $request->input('friendly_url');
            $promotionDesc->friendly_title = $request->input('friendly_title');
            $promotionDesc->metakey = $request->input('metakey');
            $promotionDesc->metadesc = $request->input('metadesc');
            $promotionDesc->lang = $request->input('lang');
            $promotionDesc->save();
        }
    }
 }