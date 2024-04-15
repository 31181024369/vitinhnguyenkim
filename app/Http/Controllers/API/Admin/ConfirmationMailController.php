<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Mail\MailNotify;
use Illuminate\Http\Request;
use Mail;
use App\Helpers\Helper;
class ConfirmationMailController extends Controller
{
    public function index()
    {
        $data = [
            'subject' => 'PCNguyenKim',
            'body' => 'Cảm ơn bạn đã dành thời gian để đăng ký mua hàng bên chúng tôi',
        ];
        // Helper::sendMail($to,$data);
        try{
            Mail::to('thanh07345@gmail.com')->send(new MailNotify($data));
            return response()->json([
                        'status' => true,
                        'message' => 'Gửi mail thành công'
            ]);
        }catch(\Exception $e)
        {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
