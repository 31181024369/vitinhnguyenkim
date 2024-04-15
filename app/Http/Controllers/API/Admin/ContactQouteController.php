<?php

namespace App\Http\Controllers\API\Admin;
use Carbon\Carbon;
use App\Models\ContactQoute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\AbstractController;
use Illuminate\Support\Facades\Http;

class ContactQouteController extends AbstractController
 {
    protected function getModel()
    {
        return new ContactQoute();
    }
    public function index()
    {
        try {
        
            $contactQoute = ContactQoute::get();
            $response = [
                'status' => 'success',
                'list' => $contactQoute,
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
        $contactQoute = new ContactQoute();
        try {
            $contactQoute->fill([
                'name' => $request->input('name'),
                'phone'=> $request->input('phone'),
                'email' =>  $request->input('email'),
                'company' => $request->input('company'),
                'address' => $request->input('address'),
                'content' => $request->input('content'),
                'attach_file' => $request->input('attach_file'),
                'status' => $request->input('status'),
                'menu_order' => $request->input('menu_order'),
                'lang' => $request->input('lang'),
            ])->save();
            $response = [
                'status' => 'success',
                'contactQoute' => $contactQoute,
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
        $list = ContactQoute::Find($id)->delete();
    }
    public function edit($id)
    {
        $contactQoute = ContactQoute::Find($id)->get();
          return response()->json([
            'status'=> true,
            'contactQoute' => $contactQoute
        ]);
    }
    public function update(Request $request, $id)
    { 
        $contactQoute = new ContactQoute();
        $listContactQoute = ContactQoute::Find($id);
        try {
            $listContactQoute->fill([
                'name' => $request->input('name'),
                'phone'=> $request->input('phone'),
                'email' =>  $request->input('email'),
                'company' => $request->input('company'),
                'address' => $request->input('address'),
                'content' => $request->input('content'),
                'attach_file' => $request->input('attach_file'),
                'status' => $request->input('status'),
                'menu_order' => $request->input('menu_order'),
                'lang' => $request->input('lang'),
            ])->save();
            $response = [
                'status' => 'success',
                'contactQoute' => $listContactQoute,
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