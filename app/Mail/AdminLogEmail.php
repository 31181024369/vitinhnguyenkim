<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AdminLogExport;
use Illuminate\Support\Facades\Storage;

class AdminLogEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    private $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $fileName = 'adminlog_'.date('Y_m_d_H_i_s').'.xlsx';

        $export = (new AdminLogExport($this->data));
        Excel::store($export, $fileName, 'public');
        $fileUrl = Storage::url($fileName);
      


        $file=public_path('storage/'.$fileName);
        return $this->view('email.adminLog')
        ->attach($file);
    }
}
