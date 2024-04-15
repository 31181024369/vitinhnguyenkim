<?php

namespace App\Exports;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Contact;
use Maatwebsite\Excel\Concerns\FromCollection;

class ContactExport implements FromCollection, WithMapping, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Contact::all();
    }
    public function map($row): array
    {
        return [
            $row->id,
            $row->subject,
            $row->staff_id,
            $row->content,
            $row->name,
            $row->email,
            $row->phone,
            $row->address,

            $row->status,
            $row->menu_order,
            $row->lang,
          
            
            
        ];
    }
    public function headings(): array
    {
        return [
            'id',
            'subject',
            'staff_id',
            'content',
            'name',
            'email',
            'phone',
            'address',
            'status',
            'menu_order',
            'lang',
        ];
    }
}
