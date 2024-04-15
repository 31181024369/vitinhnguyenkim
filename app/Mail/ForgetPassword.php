<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgetPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $diem = null;
    public function __construct($data)
    {
        $this->diem = $data;
    }

    public function build()
    {
        return $this->view('email.forget-password');
    }
}
