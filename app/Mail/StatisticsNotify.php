<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use App\Exports\BuildPCExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StatisticsSaleExport;
use App\Exports\StatisticsFileExport;


class StatisticsNotify extends Mailable
{
    use Queueable, SerializesModels;

    private $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        $fileName = 'statistics_'.date('Y_m_d_H_i_s').'.xlsx';
        // $export = (new StatisticsSaleExport);
        $export = (new StatisticsFileExport($this->data));
        Excel::store($export, $fileName, 'public');
        $fileUrl = Storage::url($fileName);
       
        //return Excel::download($export, 'statistics.xlsx');


        $file=public_path('storage/'.$fileName);
        $this->view('email.statistics')
        ->attach($file);
        // Storage::delete('public/'. $fileName);
        return true;
       
    }
}