<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PartnerIntroduce;
class PartnerIntroduceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getPartnerIntroduce(){
        try{
            $partnerIntroduce=PartnerIntroduce::orderBy('id','desc')->get();
            $firstElement = $partnerIntroduce->first();
            return response()->json([
             'status'=>true,
             'data'=> $firstElement
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
    public function index()
    {
        try {
          
            $query=PartnerIntroduce::orderBy('id','desc')->get();
           
            return response()->json([
                'status'=>true,
                'PartnerIntroduce'=>$query,
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
            $partnerIntro=new PartnerIntroduce();
           
            $partnerIntro->introduce = $request->input('introduce');
            $partnerIntro->thanks = $request->input('thanks');
            $partnerIntro->save();
            $response = [
                'status' => 'success',
                'partnerIntro' =>  $partnerIntro,
                'id'=>$partnerIntro->id
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
            $partnerIntroduce=PartnerIntroduce::where('id',$id)->first();
            return response()->json([
             'status'=>true,
             'data'=>$partnerIntroduce
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
           
            $partnerIntro = PartnerIntroduce::where('id',$id)->first();
           
            
          
            $partnerIntro->introduce = $request->input('introduce');
            $partnerIntro->thanks = $request->input('thanks');
           
            $partnerIntro->save();
            $response = [
                'status' => 'success',
                'partnerIntro' =>  $partnerIntro,
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
            PartnerIntroduce::where('id',$id)->delete();
            
            return response()->json([
             'status'=>true,
             'mess'=>'delete PartnerIntroduce success'
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
