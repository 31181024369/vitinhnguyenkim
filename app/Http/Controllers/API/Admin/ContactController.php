<?php

namespace App\Http\Controllers\API\Admin;
use Carbon\Carbon;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
// use App\Http\Controllers\API\AbstractController;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Exports\ContactExport;

class ContactController extends AbstractController
{
    protected function getModel()
    {
        return new Contact();
    }

    public function index()
    {
        try {
            $contact = Contact::with('contactStaff')->get();
            $response = [
                'status' => 'success',
                'list' => $contact,
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
    public function destroy($id)
    {
        $list = Contact::Find($id)->delete();
    }
    public function edit($id)
    {
        $contact = Contact::with('contactStaff')->get();
          return response()->json([
            'status'=> true,
            'contact' => $contact
        ]);
    }
    public function export(){
        $fileName = 'contact_'.date('Y_m_d_H_i_s').'.xlsx';
        $export = (new ContactExport);
        Excel::store($export, $fileName, 'public');
        $fileUrl = Storage::url($fileName);
        return Excel::download($export, 'contact.xlsx');
    }
}