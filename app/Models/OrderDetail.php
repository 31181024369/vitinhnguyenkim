<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;
    protected $table = 'order_detail';
    protected $timestamp = true;
    public function product()
    {
        return $this->belongsTo(Product::class,'item_id','product_id');
    }
    public function productDesc()
    {
        return $this->belongsTo(ProductDesc::class,'item_id','product_id');
    }
}
