<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use \Maatwebsite\Excel\Sheet;
class OrderSumExcelExport implements FromView, WithColumnFormatting, WithColumnWidths, WithStyles, WithEvents

{
    use RegistersEventListeners;
    
    private $data;
   
    public function __construct($data)
    {
       $this->data = $data;
     
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $lastColumn = $event->sheet->getHighestColumn();
                $lastRow = $event->sheet->getHighestRow();
        
                $range = 'A1:' . $lastColumn . $lastRow;
        
                $event->sheet->getStyle($range)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '#000000'],
                        ],
                    ],
                ]);
                $event->sheet->getStyle($range)->getAlignment()->setHorizontal('center');
            }
        ];
    }

    public function columnFormats(): array
    {
        return [
            // F is the column
            'B' => '0'
        ];
    }
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getStyle('B1')->getFont()->setBold(true);
        $sheet->getStyle('C1')->getFont()->setBold(true);
        $sheet->getStyle('D1')->getFont()->setBold(true);
        $sheet->getStyle('E1')->getFont()->setBold(true);
        $sheet->getStyle('F1')->getFont()->setBold(true);
        $sheet->getStyle('G1')->getFont()->setBold(true);
        $sheet->getStyle('H1')->getFont()->setBold(true);
        $sheet->getStyle('I1')->getFont()->setBold(true);
        $sheet->getStyle('J1')->getFont()->setBold(true);
        $sheet->getStyle('K1')->getFont()->setBold(true);
        $sheet->getStyle('L1')->getFont()->setBold(true);
        $sheet->getStyle('M1')->getFont()->setBold(true);
        $sheet->getStyle('N1')->getFont()->setBold(true);
        $sheet->getStyle('O1')->getFont()->setBold(true);
        $sheet->getStyle('P1')->getFont()->setBold(true);
        $sheet->getStyle('Q1')->getFont()->setBold(true);
    }
   
    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 30,
            'C' => 20,
            'D' => 60,   
            'E' => 20,
            'F' => 30,   
            'G' => 20,
            'H' => 20,   
            'I' => 20,
            'J' => 30,
            'K' => 20,   
            'L' => 20,
            'M' => 30,
            'N' => 30,   
            'O' => 20,
            'P' => 20,
            'Q' => 20,

            'R' => 50,   
            'S' => 50,
            'T' => 50,
            'U' => 50,   
            'V' => 50,
            'W' => 50,
            'X' => 50,
            'Y' => 50,
            'Z' => 50,
            'AA' => 50,   
            'AB' => 50,
            'AC' => 50,
            'AD' => 50,

        ];
    }
    public function view():View
    {
        return view('exports.sumOrderExcel',[
            'data' => $this->data
        ]);
    }
}
