<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPicture extends Model
{
    use HasFactory;
    protected $table='product_picture';
    protected $primaryKey = 'id';
    public function product()
    {
        return $this->belongsTo(Product::class,'product_id', 'pid');
    }
}
