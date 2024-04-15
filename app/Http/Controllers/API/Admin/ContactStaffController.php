<?php

namespace App\Http\Controllers\API\Admin;
use Carbon\Carbon;
use App\Models\ContactStaff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\AbstractController;
use Illuminate\Support\Facades\Http;

class ContactStaffController extends AbstractController
 {
    protected function getModel()
    {
           return new ContactStaff();
       }

       public function index()
    {
        try {
        
            $contactStaff = ContactStaff::get();
            $response = [
                'status' => 'success',
                'list' => $contactStaff,
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
        $contactStaff = new ContactStaff();
        try {
            $contactStaff->title = $request->input('title');
            $contactStaff->email = $request->input('email');
            $contactStaff->phone = $request->input('phone');
            $contactStaff->description = $request->input('description');
            $contactStaff->menu_order = $request->input('menu_order');
            $contactStaff->display = $request->input('display');
            $contactStaff->adminid = $request->input('adminid');
            $contactStaff->lang = $request->input('lang');
            $contactStaff->save();

            $response = [
                'status' => 'success',
                'contactStaff' => $contactStaff,
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
        $list = ContactStaff::Find($id)->delete();
    }
    public function edit($id)
    {
        $contactStaff = ContactStaff::get();
          return response()->json([
            'status'=> true,
            'contactStaff' => $contactStaff
        ]);
    }
    public function update(Request $request, $id)
    {   
        $contactStaff = new ContactStaff();
        $listContactStaff = ContactStaff::Find($id);
        
        $listContactStaff->title = $request->input('title');
        $listContactStaff->email = $request->input('email');
        $listContactStaff->phone = $request->input('phone');
        $listContactStaff->description = $request->input('description');
        $listContactStaff->menu_order = $request->input('menu_order');
        $listContactStaff->display = $request->input('display');
        $listContactStaff->adminid = $request->input('adminid');
        $listContactStaff->lang = $request->input('lang');
        $listContactStaff->save();
    }
 }