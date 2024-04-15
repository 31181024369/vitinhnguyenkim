<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use \Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;

class AdminLogExport implements FromView, WithColumnWidths, WithEvents
{
    use RegistersEventListeners;
    private $data;
    public function __construct($data)
    {
        $this->data = $data;
    }
    public static function afterSheet(AfterSheet $event)
    {

    }
    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 20,
            'C' => 10,
            'D' => 10,   
            'E' => 20,
            'F' => 180,   
    
        ];
    }
    public function view():View
    {
        return view('exports.adminLog',[
            'data' => $this->data
        ]);
    }
}
