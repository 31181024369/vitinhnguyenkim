<?php

namespace App\Http\Controllers\API\Admin;
use Carbon\Carbon;
use App\Models\ContactConfig;
use App\Models\ContactConfigDesc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\AbstractController;
use Illuminate\Support\Facades\Http;

class ContactConfigController extends AbstractController
 {
    protected function getModel()
    {
           return new ContactConfig();
       }

    public function index()
    {
        try {
        
            $contactConfig = ContactConfig::with('contactConfigDesc')->get();
            $response = [
                'status' => 'success',
                'list' => $contactConfig,

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
        $contactConfig = new ContactConfig();
        $contactConfigDesc = new ContactConfigDesc();
        try {
            $contactConfig->fill([
                'company' => $request->input('company'),
                'address'=> $request->input('address'),
                'phone' =>  $request->input('phone'),
                'fax' => $request->input('fax'),
                'email' => $request->input('email'),
                'email_order' => $request->input('email_order'),
                'website' => $request->input('website'),
                'work_time' => $request->input('work_time'),
                'map_lat' => $request->input('map_lat'),
                'map_lng' => $request->input('map_lng'),
                'menu_order' => $request->input('menu_order'),
                'display' => $request->input('display'),
                'adminid' => $request->input('adminid')
            ])->save();
            $contactConfigDesc->contact_id = $contactConfig->contact_id;
            $contactConfigDesc->title = $request->input('title');
            $contactConfigDesc->map_desc = $request->input('map_desc');
            $contactConfigDesc->map_address = $request->input('map_address');
            $contactConfigDesc->lang = $request->input('lang');
            $contactConfigDesc->save();

            $response = [
                'status' => 'success',
                'faqs' => $contactConfig,
                'faqsDesc' => $contactConfigDesc,
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
        $list = ContactConfig::Find($id)->delete();
    }
    public function edit($id)
    {
        $contactConfig = ContactConfig::with('contactConfigDesc')->get();
        return response()->json([
            'status'=> true,
            'faqs' => $contactConfig
        ]);
    }
    public function update(Request $request, $id)
    { 
        $contactConfig = new ContactConfig();
        $contactConfigDesc = new ContactConfigDesc();
        $listContactConfig = ContactConfig::Find($id);
        try {
            $listContactConfig->fill([
                'company' => $request->input('company'),
                'address'=> $request->input('address'),
                'phone' =>  $request->input('phone'),
                'fax' => $request->input('fax'),
                'email' => $request->input('email'),
                'email_order' => $request->input('email_order'),
                'website' => $request->input('website'),
                'work_time' => $request->input('work_time'),
                'map_lat' => $request->input('map_lat'),
                'map_lng' => $request->input('map_lng'),
                'menu_order' => $request->input('menu_order'),
                'display' => $request->input('display'),
                'adminid' => $request->input('adminid')
            ])->save();
            $contactConfigDesc = ContactConfigDesc::where('contact_id', $id)->first();
            if ($contactConfigDesc) {
                $contactConfigDesc->title = $request->input('title');
                $contactConfigDesc->map_desc = $request->input('map_desc');
                $contactConfigDesc->map_address = $request->input('map_address');
                $contactConfigDesc->lang = $request->input('lang');
                $contactConfigDesc->save();
            }

            $response = [
                'status' => 'success',
                'contactConfig' => $listContactConfig,
                'contactConfigDesc' => $contactConfigDesc,
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