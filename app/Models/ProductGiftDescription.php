<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductGiftDescription extends Model
{
    protected $table = 'product_gift_description';
    protected $primaryKey = 'id';
    use HasFactory;
    protected $fillable = [
        'product_id',
        'list_product',
        'content',
    ];
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id','product_id');
    }
}
