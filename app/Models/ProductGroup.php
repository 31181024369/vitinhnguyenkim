<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductGroup extends Model
{
    use HasFactory;
    protected $table = 'product_group';
    protected $primaryKey = 'id_group';
    public function product()
    {
        return $this->belongsTo(Product::class,'product_main','product_id');
    }

    
}
