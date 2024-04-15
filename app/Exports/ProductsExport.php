<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromArray;

class ProductsExport implements FromQuery,WithMapping, WithHeadings
{
    use Exportable;
    // public $fromDate;
    // public $toDate;
    public $categoryId;
    public $brandId;
    // public $columns;
    public function __construct( $categoryId, $brandId)
    {
        
        $this->categoryId = $categoryId;
        $this->brandId = $brandId;
       
    }
    public function query()
    {
        $categoryId=$this->categoryId;
        $brandId=$this->brandId;

        return Product::query()
        ->where(function ($query) use ($category, $brand) {
            $query->where('cat_id', '=', $categoryId)
                ->where('brand_id', '>=',$brandId);
        });
        

        
        // if($this->fromDate ||  $this->toDate || $this->status )
        // {
        //     return Product::query()
        //         ->whereBetween('created_at', [$this->fromDate, $this->toDate])
        //         ->orwhere('status', $this->status);
        // }else{
        //     return Product::query();
        // }
        // if($this->brand)
        // {
        //     $query->whereHas('anotherTable', function ($q) {
        //         $q->where('product_name', 'like', '%' . $this->productName . '%');
        //     });
        // }
        
    }
    public function map($row): array
    {
        return [
            $row->product_id,
            $row->cat_id,
            $row->cat_list,
            $row->maso,
            $row->macn,
            $row->co_script,
            $row->picture,
            $row->price,
            $row->price_old,
            $row->brand_id,
            $row->status,
            $row->options,
            $row->op_search,
            $row->cat_search,
            $row->technology,
            $row->focus,
            $row->views,
            $row->display,
            $row->adminid,
            $row->url,
          
        ];
    }
    public function headings(): array
    {
        return [
            'product_id',
            'cat_id',
            'cat_list',
            'maso',
            'macn',
            'co_script',
            'picture',
            'price',
            'price_old',
            'brand_id',
            'status',
            'options',
            'op_search',
            'cat_search',
            'technology',
            'focus',
            'views',
            'display',
            'adminid',
            'url',
        ];
    }
}
