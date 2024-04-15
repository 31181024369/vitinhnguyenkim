<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Partner;
use App\Models\PartnerImage;
class PartnerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
          
            $query=Partner::orderBy('id','desc');
            if($request->data == 'undefined' || $request->data =="")
            {
                $list=$query;
            }
            else{
                $list=$query->where('namePartner','like', '%' . $request->data . '%')
                ->orWhere('desPartner','like', '%' . $request->data . '%');
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        try {
            $partner=new Partner();
            $disPath = public_path();
            $filePath = '';
            if ( $request->logo != null ) 
            {
                $DIR = $disPath.'\uploads\partner';
                $httpPost = file_get_contents( 'php://input' );
                $file_chunks = explode( ';base64,', $request->logo[ 0 ] );
                $fileType = explode( 'image/', $file_chunks[ 0 ] );
                $image_type = $fileType[ 0 ];
                //return response()->json( $file_chunks );
                $base64Img = base64_decode( $file_chunks[ 1 ] );
                $data = iconv( 'latin5', 'utf-8', $base64Img );
                $name = uniqid();
                $file = $DIR .'\\'. $name . '.png';
                $filePath = 'partner/'.$name . '.png';
                file_put_contents( $file,  $base64Img );
            }
            $partner->logo = $filePath;
            $partner->namePartner = $request->input('namePartner');
            $partner->desPartner = $request->input('desPartner');
            $partner->url =  $request->input('url');
            $partner->save();
            $response = [
                'status' => 'success',
                'partner' =>  $partner,
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
            $partner=Partner::with('PartnerImage','PartnerNews')->where('id',$id)->first();
            return response()->json([
             'status'=>true,
             'data'=>$partner
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
            $partner = Partner::where('id',$id)->first();
            $filePath = '';
            
            if ( $request->logo != null && $request->logo != $partner ->logo) 
            {
                $DIR = $disPath.'\uploads\partner';
                $httpPost = file_get_contents( 'php://input' );
                $file_chunks = explode( ';base64,', $request->logo[ 0 ] );
                $fileType = explode( 'image/', $file_chunks[ 0 ] );
                $image_type = $fileType[ 0 ];
                //return response()->json( $file_chunks );
                $base64Img = base64_decode( $file_chunks[ 1 ] );
                $data = iconv( 'latin5', 'utf-8', $base64Img );
                $name = uniqid();
                $file = $DIR .'\\'. $name . '.png';
                $filePath = 'partner/'.$name . '.png';
                file_put_contents( $file,  $base64Img );
            }
            else{
                $filePath =$partner->logo;
            }
            $partner->logo = $filePath;
            $partner->namePartner = $request->input('namePartner');
            $partner->desPartner = $request->input('desPartner');
            $partner->url =  $request->input('url');
            $partner->save();
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            Partner::where('id',$id)->delete();
            PartnerImage::where( 'partner_id', $id )->delete();
            return response()->json([
             'status'=>true,
             'mess'=>'delete Partner success'
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
