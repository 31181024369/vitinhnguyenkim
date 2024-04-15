<?php

namespace App\Models;

use App\Models\CategoryDesc;
use App\Models\ProductCatOptionDesc;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductCatOption extends Model
{
    use HasFactory;
    protected $table = 'product_cat_option';
    protected $primaryKey = 'op_id';
    protected $fillable = [
        'cat_id',
        'parentid',
        'is_search',
        'is_detail',
        'is_hover',
        'is_focus',
        'is_warranty',
        'menu_order',
        'display',
        'date_post',
        'date_update',
        'adminid'
        
    ];

    
    public function category()
    {
        return $this->belongsTo(Category::class,'cat_id','cat_id');
    }
    public function catOptionDesc()
    {
        return $this->hasOne(ProductCatOptionDesc::class, 'op_id', 'op_id');
    }
    
    public function subCateOption()
    {
        return $this->hasMany(ProductCatOption::class, 'parentid', 'op_id');
    }
    //Product co cat_id cua category
///Category thi co catoption
    
}
