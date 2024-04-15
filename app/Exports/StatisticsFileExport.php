<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StatisticsFileExport implements FromView
{
    private $data;
    public function __construct($data)
    {
        $this->data = $data;
    }
    public function view():View
    {
        return view('exports.statistics',[
            'data' => $this->data
        ]);
    }
}
