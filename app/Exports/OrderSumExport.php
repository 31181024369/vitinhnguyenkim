<?php
namespace App\Exports;

use App\Models\OrderSum;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrderSumExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;

    public $mem_id;
    public $status;
    public $fromDate;
    public $toDate;
    public $orderCode;

    public function __construct($mem_id, $status, $fromDate, $toDate, $orderCode)
    {
        $this->mem_id = $mem_id;
        $this->status = $status;
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
        $this->orderCode = $orderCode;
    }

    public function query()
    {
        $mem_id = $this->mem_id;
        $status = $this->status;
        $fromDate = $this->fromDate;
        $toDate = $this->toDate;
        $orderCode = $this->orderCode;
        return OrderSum::query()
            ->where(function ($query) use ($mem_id, $status, $fromDate, $toDate, $orderCode) {
                $query->where('mem_id', 'LIKE', '%' . $mem_id . '%')
                    ->orwhere('date_order', '>=', $fromDate)
                    ->orwhere('date_order', '<=', $toDate)
                    ->orwhere('order_code', 'LIKE', '%' . $orderCode . '%')
                    ->orwhere('status', 'LIKE', '%' . $status . '%');
            });
    }

    public function map($row): array
    {
        return [
            $row->order_id,
            $row->order_code,
            $row->d_name,
            $row->d_address,
            $row->d_phone,
            $row->d_email,
            $row->c_name,
            $row->c_address,
            $row->c_phone,
            $row->c_email,
            $row->total_cart,
            $row->total_price,
            $row->shipping_method,
            $row->payment_method,
            $row->date_order,
            $row->ship_date,
            $row->status,
        ];
    }
    public function headings(): array
    {
        return [
            'Order ID',
            'Order Code',
            'Name',
            'Address',
            'Phone',
            'Email',
            'Company Name',
            'Company Address',
            'Company Phone',
            'Company Email',
            'Total Cart',
            'Total Price',
            'Shipping Method',
            'Payment Method',
            'Date Order',
            'Ship Date',
            'Status',
        ];
    }
}