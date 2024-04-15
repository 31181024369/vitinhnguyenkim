<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Coupon;

class CouponExport implements FromCollection, WithMapping, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Coupon::all();
    }
    public function map($row): array
    {
        return [
            $row->id,
            $row->TenCoupon,
            $row->MaPhatHanh,
            $row->StartCouponDate,
            $row->EndCouponDate,
            $row->DesCoupon,
            $row->GiaTriCoupon,
            $row->MaxValueCoupon,
            $row->SoLanSuDung,
            $row->KHSuDungToiDa,
            $row->SuDungDongThoi,
            $row->DonHangChapNhanTu,
            $row->DanhMucSpChoPhep,
            $row->ThuongHieuSPApDung,
            $row->LoaiKHSuDung,
            $row->mem_id,
            $row->DateCreateCoupon,
            $row->MaKhoSPApdung,
            $row->coupon_desc,
            $row->ThuongHieuSPApDung,
            
        ];
    }
    public function headings(): array
    {
        return [
            'id',
            'TenCoupon',
            'MaPhatHanh',
            'StartCouponDate',
            'EndCouponDate',
            'DesCoupon',
            'GiaTriCoupon',
            'MaxValueCoupon',
            'SoLanSuDung',
            'KHSuDungToiDa',

            'SuDungDongThoi',
            'DonHangChapNhanTu',
            'DanhMucSpChoPhep',
            'ThuongHieuSPApDung',
            'LoaiKHSuDung',

            'mem_id',
            'DateCreateCoupon',
            'MaKhoSPApdung',
            'coupon_desc',
            'ThuongHieuSPApDung',
        ];
    }

}
