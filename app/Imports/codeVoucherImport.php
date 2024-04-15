<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class codeVoucherImport implements ToCollection
{
    protected $data;

    public function collection(Collection $rows)
    {
        if ($rows != null) {
            $this->data = $rows;
        }
    }

    public function ignoreFirstRow()
    {
        if ($this->data != null && $this->data->count() > 0) {
            $this->data->shift(); // Xóa dòng đầu tiên
        }
    }

    public function getDataByRows()
    {
        if ($this->data != null && $this->data->count() > 0) {
            return $this->data->toArray();
        }

        return [];
    }
}
