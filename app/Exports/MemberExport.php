<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Member;

class MemberExport implements FromCollection, WithMapping, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Member::all();
    }
    public function map($row): array
    {
        return [
            $row->mem_id,
            $row->username,
            $row->full_name,
            $row->email,
            $row->phone,
            $row->city_province,
            $row->ward,
            $row->district,

            $row->MaKH,
            $row->MaKHDinhDanh,
            $row->status,
            $row->m_status,
            $row->created_at,
            $row->last_login,
            
            
        ];
    }
    public function headings(): array
    {
        return [
            'mem_id',
            'username',
            'full_name',
            'email',
            'phone',
            'city_province',
            'ward',
            'district',
            'MaKH',
            'MaKHDinhDanh',
            'status',
            'm_status',
            'created_at',
            'last_login',
        ];
    }
}
