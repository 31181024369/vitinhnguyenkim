<?php

namespace App\Exports;


use Illuminate\Contracts\View\View;
// use Maatwebsite\Excel\Concerns\ToModel;
// use Maatwebsite\Excel\Concerns\WithMapping;
// use Maatwebsite\Excel\Concerns\FromCollection;
// use Maatwebsite\Excel\Concerns\WithHeadings;
// use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
// use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use \Maatwebsite\Excel\Sheet;


class BuildPCExport implements FromView, WithDrawings, WithEvents
{ 
    use RegistersEventListeners;
    
    private $data;
   
    public function __construct($data)
    {
       $this->data = $data;
     
    }
    
    public static function afterSheet(AfterSheet $event)
    {
      
        // Create Style Arrays
        $default_font_style = [
            'font' => ['name' => 'Arial', 'size' => 10],
            'background' => [
                'color'=> '#808080'
            ]
        ];

        $strikethrough = [
            'font' => ['bold' => true],
        ];
        $borders=[  'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],

        ],];
        $backgroup_color=['argb' => [
            'color'=> '#808080'
        ],];
        $styleArray =  ['fill' => [
            'color' => array('rgb' => '000000')
        ]];


   
        // Get Worksheet
        $active_sheet = $event->sheet->getDelegate();


        //-------------------------------------------------
        $lastColumn = $event->sheet->getHighestColumn();
        $lastRow = $event->sheet->getHighestRow();
        $range = 'A20:' . $lastColumn . $lastRow;
        $event->sheet->getStyle($range)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '#5a9f3d'],
                ],
            ],
        ]);
        $active_sheet->getStyle($lastRow-1)->getAlignment()->setHorizontal('center');
        $active_sheet->getStyle($lastRow)->getAlignment()->setHorizontal('center');

        // $active_sheet->getStyle($lastRow-1)->applyFromArray($styleArray);
        // $active_sheet->getStyle($lastRow-1)->applyFromArray($borders);
        $active_sheet->getStyle($lastRow-1)->applyFromArray($strikethrough);

        // $active_sheet->getStyle($lastRow)->applyFromArray($styleArray);
        // $active_sheet->getStyle($lastRow)->applyFromArray($borders);
        $active_sheet->getStyle($lastRow)->applyFromArray($strikethrough);


        $range1= $lastColumn . $lastRow-2;
        $active_sheet->getStyle($range1)->applyFromArray([
            'font' => [
                'name'      =>  'Calibri',
                'size'      =>  12,
                'bold'      =>  true,
                'color' => ['argb' => 'EB2B02'],
            ],

        ]);


        $active_sheet->getRowDimension('1')->setRowHeight(30);
        $active_sheet->getRowDimension('2')->setRowHeight(30);
        $active_sheet->getRowDimension('3')->setRowHeight(30);
        $active_sheet->getRowDimension('4')->setRowHeight(30);
        $active_sheet->getRowDimension('5')->setRowHeight(30);
        $active_sheet->getRowDimension('6')->setRowHeight(30);

        $active_sheet->getStyle('A10:E10')->applyFromArray([
            'font' => [
                'name'      =>  'Calibri',
                'size'      =>  20,
                'bold'      =>  true,
                'color' => ['argb' => '#5a9f3d'],
            ],

        ]);
        $active_sheet->getStyle('A10:E10')->getAlignment()->setHorizontal('center');
         $event->sheet->getDelegate()->getRowDimension('10')->setRowHeight(30);

         $active_sheet->getStyle('A11:E11')->applyFromArray([
            'font' => [
                'name'      =>  'Calibri',
                'size'      =>  13,
                'bold'      =>  true,
                'color' => ['argb' => '#5a9f3d'],
            ],

        ]);
        $event->sheet->getDelegate()->getRowDimension('11')->setRowHeight(20);

        $active_sheet->getStyle('A12:E12')->applyFromArray([
            'font' => [
                'name'      =>  'Calibri',
                'size'      =>  13,
                'bold'      =>  true,
                'color' => ['argb' => '#5a9f3d'],
            ],

        ]);
        $event->sheet->getDelegate()->getRowDimension('12')->setRowHeight(20);


       

        //------------------------------------------------
       

        // Apply Style Arrays
        // $active_sheet->getParent()->getDefaultStyle()->applyFromArray($default_font_style);

        // strikethrough group of cells (A10 to B12)
       
       

        $active_sheet->getStyle('A14:E14')->applyFromArray($styleArray);
        $active_sheet->getStyle('A14:E14')->applyFromArray($borders);
        $active_sheet->getStyle('A14:E14')->applyFromArray($strikethrough);
        // $active_sheet->getStyle('A14:E14')->getAlignment()->setHorizontal('center');

        $active_sheet->getStyle('A15:E15')->applyFromArray($styleArray);
        $active_sheet->getStyle('A15:E15')->applyFromArray($borders);
        $active_sheet->getStyle('A15:E15')->applyFromArray($strikethrough);

        $active_sheet->getStyle('E15')->applyFromArray([
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '5a9f3d',
                 ]           
            ],

        ]);
        $active_sheet->getStyle('E15')->applyFromArray([
            'font' => [
                'name'      =>  'Calibri',
                'size'      =>  11,
                'bold'      =>  true,
                'color' => ['argb' => 'fff0ed'],
            ],

        ]);
        $active_sheet->getStyle('E15')->getAlignment()->setHorizontal('center');
        $active_sheet->getRowDimension('15')->setRowHeight(20);


        $active_sheet->getStyle('A16:E16')->applyFromArray($styleArray);
        $active_sheet->getStyle('A16:E16')->applyFromArray($borders);
        $active_sheet->getStyle('A16:E16')->applyFromArray($strikethrough);
        
        $active_sheet->getStyle('A17:E17')->applyFromArray($styleArray);
        $active_sheet->getStyle('A17:E17')->applyFromArray($borders);
        $active_sheet->getStyle('A17:E17')->applyFromArray($strikethrough); //A10:B12

        $active_sheet->getStyle('A19:E19')->applyFromArray($styleArray);
        // $active_sheet->getStyle('A19:E19')->applyFromArray($borders);
        $active_sheet->getStyle('A19:E19')->applyFromArray($strikethrough);
        $active_sheet->getStyle('A19:E19')->applyFromArray([
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '5a9f3d',
                 ]           
            ],

        ]);
        $active_sheet->getStyle('A19:E19')->applyFromArray([
            'font' => [
                'name'      =>  'Calibri',
                'size'      =>  11,
                'bold'      =>  true,
                'color' => ['argb' => 'fff0ed'],
            ],

        ]);
        $active_sheet->getStyle('A19:E19')->getAlignment()->setHorizontal('center');
         $event->sheet->getDelegate()->getRowDimension('19')->setRowHeight(20);
        // $active_sheet->getStyle('A13:E13')->getFont()->setSize(14);
        //$event->sheet->getDelegate()->getRowDimension('1')->setRowHeight(40);

        $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(70);
        $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(30);
        $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(20);
        $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(30);
       
        
    }

    // public function collection()
    // {  
       

    //     return collect([$this->data]);
    // }
    // public function startCell(): string
    // {
    //     //return 'B10';
    //     return 'A10';

    // }
    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is my logo');
        $drawing->setPath(public_path('/logoNK/logo.png'));
        $drawing->setHeight(250);
        $drawing->setWidth(900);
        $drawing->setCoordinates('A1');

        return $drawing ;
    }
   
    // public function headings(): array
    // {
    //     return [
    //         'stt',
    //         'Mã hàng',
    //         'Tên sản phẩm',
    //         'Cấu hình chi tiết',
    //         'Gía(vat)',
    //         'SL',
    //         'Thành Tiền',
    //         'Bảo Hành',
    //         'Khuyến mãi'

    //     ];
    // }
    public function view():View
    {
        return view('exports.buildPCExcel',[
            'data' => $this->data
        ]);
    }
    

  
   
 
}