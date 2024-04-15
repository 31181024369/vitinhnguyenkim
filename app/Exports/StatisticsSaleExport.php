<?php

namespace App\Exports;
use App\Models\StatisticsPages;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StatisticsSaleExport implements FromCollection, WithMapping, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
        return StatisticsPages::all();

    }
    public function map($row): array
    {
        return [
            $row->id_static_page,
            $row->uri,
            $row->date,
            $row->count,
            $row->id,
            $row->module,
            $row->action,
            $row->friendly_url,
            $row->create_at,
            $row->updated_at,
            
        ];
    }
    public function headings(): array
    {
        return [
            'id_static_page',
            'uri',
            'date',
            'count',
            'id',
            'module',
            'action',
            'friendly_url',
            'created_at',
            'updated_at',
        ];
    }

}
