<?php

namespace App\Http\Controllers\API\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Partner;
use App\Models\PartnerNews;

class PartnerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
    public function detailPartnerNews(Request $request,$slug){
        try{
            $partner=PartnerNews::where('friendly_url',$slug)->first();
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
    public function detailPartner(Request $request,$slug){
        try{
            $partner=Partner::with('PartnerImage','PartnerNews')->where('url',$slug)->first();
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
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
