<?php

namespace App\Exports;

use App\Models\StatisticsPages;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StatisticsExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;
    public $id;
    public $fromDate;
    public $toDate;
   

    public function __construct($id,$fromDate, $toDate)
    {
        $this->id = $id;
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
    }
    public function query()
    {
        $id = $this->id;
        $fromDate = $this->fromDate;
        $toDate = $this->toDate;
        return StatisticsPages::query()
            ->where(function ($query) use ($id, $fromDate, $toDate) {
                $query->where('id', 'LIKE', '%' . $id . '%')
                ->orwhere('date', '>=', $fromDate)
                ->orwhere('date', '<=', $toDate);
            });
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
