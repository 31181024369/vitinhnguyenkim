<?php

namespace App\Models;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ProductFlashSale extends Model
{
    protected $table = 'product_flash_sale';
    protected $primaryKey = 'id';
    use  HasFactory;
   
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'price',
        'price_old',
        'discount_percent',
        'discount_price',
        'time',
        'status',
        'adminid'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id','product_id');
    }
}