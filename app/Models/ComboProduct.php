<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComboProduct extends Model
{
    use HasFactory;
    protected $table = 'combo_products';
    protected $fillable=[
        'product_id',
        'nameCombo',
        'priceCombo',
        'description',
        'image',
        'status'
    ];
}
