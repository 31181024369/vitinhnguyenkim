<?php

namespace App\Models;

use App\Models\ProductCatOption;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductCatOptionDesc extends Model
{
    use HasFactory;

    protected $table = 'product_cat_option_desc';
    protected $primaryKey = 'id';
    use  HasFactory;

    protected $fillable = [
        'op_id',
        'title',
        'slug',
        'description',
        'lang'
       
    ];
    
    
}
