<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PartnerNews;
use App\Models\Partner;
class PartnerNewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getPartner(Request $request){
        try{
            $partner=Partner::orderBy('id','desc')->get();
            $response = [
                'status' => 'success',
                'partner' =>  $partner,
            ];
            return response()->json( $response, 200 );
        }catch ( \Exception $e ) {
            $errorMessage = $e->getMessage();
            $response = [
                'status' => 'false',
                'error' => $errorMessage
            ];
            return response()->json( $response, 500 );
        }  

    }
    public function index(Request $request)
    {
        try {
          
            $query=PartnerNews::with('partner')->orderBy('id','desc');
            if($request->data == 'undefined' || $request->data =="")
            {
                $list=$query;
            }
            else{
                $list=$query->where('title','like', '%' . $request->data . '%');
            }
            $partnerList=$list->paginate(10);
            return response()->json([
                'status'=>true,
                'partnerList'=>$partnerList,
            ]);
        } catch ( \Exception $e ) {
            $errorMessage = $e->getMessage();
            $response = [
                'status' => 'false',
                'error' => $errorMessage
            ];
            return response()->json( $response, 500 );
        }  
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // try {
        //     $disPath = public_path();
        //     $partnerNews = new PartnerNews();
        //     $filePath = '';
        //     if ( $request->picture != null ) 
        //     {
        //         $DIR = $disPath.'\uploads\partnerNews';
        //         $httpPost = file_get_contents( 'php://input' );
        //         $file_chunks = explode( ';base64,', $request->picture[ 0 ] );
        //         $fileType = explode( 'image/', $file_chunks[ 0 ] );
        //         $image_type = $fileType[ 0 ];
        //         //return response()->json( $file_chunks );
        //         $base64Img = base64_decode( $file_chunks[ 1 ] );
        //         $data = iconv( 'latin5', 'utf-8', $base64Img );
        //         $name = uniqid();
        //         $file = $DIR .'\\'. $name . '.png';
        //         $filePath = 'partnerNews/'.$name . '.png';
        //         file_put_contents( $file,  $base64Img );
        //     }
       
        //     $partnerNews->partner_id = $request->input('partner_id');
        //     $partnerNews->title = $request->input( 'title' );
        //     $partnerNews->description = $request->input('description');
        //     $partnerNews->short = $request->input( 'short' );
        //     $partnerNews->friendly_url = $request->input( 'friendly_url' );
        //     $partnerNews->friendly_title = $request->input( 'friendly_title' );
        //     $partnerNews->metakey = $request->input( 'metakey' );
        //     $partnerNews->metadesc = $request->input( 'metadesc' );
        //     $partnerNews->save();
        //     $response = [
        //         'status' => 'success',
        //         'partnerNews' => $partnerNews,
        //     ];
        //     return response()->json( $response, 200 );
        // } catch ( \Exception $e ) {
        //     $errorMessage = $e->getMessage();
        //     $response = [
        //         'status' => 'false',
        //         'error' => $errorMessage
        //     ];
        //     return response()->json( $response, 500 );
        // }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try{
            $partnerNews = PartnerNews::where('id',$id)->first();

            return response()->json([
             'status'=>true,
             'data'=>$partnerNews
            ]);

        }catch ( \Exception $e ) {
            $errorMessage = $e->getMessage();
            $response = [
                'status' => 'false',
                'error' => $errorMessage
            ];
    
            return response()->json( $response, 500 );
        }  
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try{
            $disPath = public_path();
            $partner = PartnerNews::where('id',$id)->first();
            $filePath = '';
            
            if ( $request->picture != null && $request->picture != $partner ->picture) 
            {
                
                $DIR = $disPath.'\uploads\partnerNews';
                $httpPost = file_get_contents( 'php://input' );
                $file_chunks = explode( ';base64,', $request->picture[ 0 ] );
                $fileType = explode( 'image/', $file_chunks[ 0 ] );
                $image_type = $fileType[ 0 ];
                //return response()->json( $file_chunks );
                $base64Img = base64_decode( $file_chunks[ 1 ] );
                $data = iconv( 'latin5', 'utf-8', $base64Img );
                $name = uniqid();
                $file = $DIR .'\\'. $name . '.png';
                $filePath = 'partnerNews/'.$name . '.png';
                file_put_contents( $file,  $base64Img );
            }
            else{
                $filePath =$partner ->picture;
            }
            $partnerNews->picture = $filePath;
            $partnerNews->partner_id = $request->input('partner_id');
            $partnerNews->title = $request->input( 'title' );
            $partnerNews->description = $request->input('description');
            $partnerNews->short = $request->input( 'short' );
            $partnerNews->friendly_url = $request->input( 'friendly_url' );
            $partnerNews->friendly_title = $request->input( 'friendly_title' );
            $partnerNews->metakey = $request->input( 'metakey' );
            $partnerNews->metadesc = $request->input( 'metadesc' );
            $partnerNews->save();
            $response = [
                'status' => 'success',
                'partnerNews' =>  $partnerNews,
            ];
            return response()->json( $response, 200 );
        }catch ( \Exception $e ) {
            $errorMessage = $e->getMessage();
            $response = [
                'status' => 'false',
                'error' => $errorMessage
            ];
    
            return response()->json( $response, 500 );
        } 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            PartnerNews::where('id',$id)->delete();
            return response()->json([
                'status'=>true,
                'mess'=>'delete PartnerNews success'
            ]);
        }catch ( \Exception $e ) {
            $errorMessage = $e->getMessage();
            $response = [
                'status' => 'false',
                'error' => $errorMessage
            ];
    
            return response()->json( $response, 500 );
        } 
    }
}
