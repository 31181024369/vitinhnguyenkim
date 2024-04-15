<?php   
namespace App\Helpers;
use Mail;
use App\Mail\MailNotify;

class Helper{
    public static function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString.= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    public static function active( $active = 0) : string
    {
        return $active == 0 ? '<span class= "btn btn-danger btn-xs">NO</span>' : 
        '<span class= "btn btn-success btn-xs"> YES</span>';    
    }
    public static function sendMail($to, $data)
    {
        Mail::to($to)->send(new MailNotify($data));
    }
}