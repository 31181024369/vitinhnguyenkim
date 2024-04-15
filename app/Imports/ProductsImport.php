<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Hash;

class ProductsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null

    */
    /**
     * Trong một số trường hợp, headings key của chúng ta không nằm ở dòng đầu tiêu, 
     * mà nằm ở dòng số 2 chả hạn thì viết thêm hàm headingRow để xử lý vấn đề này
     */
    // public function headingRow(): int
    // {
    //     return 2;
    // }
    protected $cat_id;
    public function __construct($cat_id)
    {
        $this->cat_id = $cat_id;
    }
    public function model(array $row)
    {
        return new Product([
            'cat_id' => $this->cat_id,
            'cat_list' => $row[2],
            'maso' => $row[3],
            'macn' => $row[4],
            'code_script' => $row[5],
            'picture' => $row[6],
            'price' => '500000',
            'price_old' => '500000',
            'brand_id' => '11',
            'status' => '0',
            'options' => $row[11],
            'op_search' => $row[12],
            'cat_search' => $row[13],
            'technology' => $row[14],
            'focus' => '1',
            'focus_order' => '1',
            'deal' => '1',
            'deal_order' => '1',
            'deal_date_start' => '1',
            'deal_date_end' => '1',
            'stock' => '1',
            'votes' => '1',
            'numvote' => '1',
            'menu_order' => '1',
            'menu_order_cate_lv0' => '1',
            'menu_order_cate_lv1' => '1',
            'menu_order_cate_lv2' => '1',
            'menu_order_cate_lv3' => '1',
            'menu_order_cate_lv4' => '1',
            'menu_order_cate_lv5' => '1',
            'menu_order_cate_lv6' => '1',
            'menu_order_cate_lv7' => '1',
            'menu_order_cate_lv8' => '1',
            'menu_order_cate_lv9' => '1',
            'menu_order_cate_lv10' => '1',
            'views' => '1',
            'display' => '1',
            'date_post' => '1',
            'date_update' => '1',
            'adminid' => '1',
            'url' => '1'
        ]);
    }
    public function rules(): array
    {
        return [
            '1' => 'integer',
            '2' => 'integer',
            '3' => 'string',
            '4' => 'string',
        ];
    }
}
