<?php
namespace App\Http\Controllers\API\Admin;
use Carbon\Carbon;
use App\Models\MailTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\AbstractController;
use Illuminate\Support\Facades\Http;

class MailTempController extends AbstractController
 {
    protected function getModel()
    {
           return new MailTemplate();
       }

    public function index()
    {
        try {
        
            $mailTemplate = MailTemplate::get();
            $response = [
                'status' => 'success',
                'list' => $mailTemplate,

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
 }