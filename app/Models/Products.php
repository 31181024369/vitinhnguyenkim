<?php

namespace App\Models;

use App\Models\Comment;
use App\Models\ProductDescs;
use App\Models\ProductGroup;
use App\Models\ProductCatOption;
use App\Models\ProductflashSale;
use App\Models\Price;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Products extends Model
{
    use  HasFactory;
    protected $table = 'products';
    protected $primaryKey = 'product_id';
   
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cat_id','cat_list','maso', 'macn','code_script',
        'picture','price','price_old','brand_id',
        'status','options', 'op_search',
        'cat_search',
        'technology',
        'focus',
        'focus_order',
        'deal',
        'deal_order',
        'deal_data_start',
        'deal_data_end',
        'stock',
        'votes',
        'numvote',
        'menu_order',
        'menu_order_cate_lv0',
        'menu_order_cate_lv1',
        'menu_order_cate_lv2',
        'menu_order_cate_lv3',
        'menu_order_cate_lv4',
        'menu_order_cate_lv5',
        'menu_order_cate_lv6',
        'menu_order_cate_lv7',
        'menu_order_cate_lv8',
        'menu_order_cate_lv9',
        'menu_order_cate_lv10',
        'views',
        'display',
        'date_post',
        'date_update',
        'adminid',
        'url'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($product) {
            $product->productDescs()->delete();
            $product->productFlashSale()->delete();
        });
    }
    public function categoryDes()
    {
        return $this->belongsTo(CategoryDesc::class, 'cat_id','cat_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'cat_id','cat_id');
    }
   
    public function productDescs()
    {
        return $this->hasOne(ProductDescs::class, 'product_id');
    }

    public function productFlashSale()
    {
        return $this->hasMany(productFlashSale::class,'product_id','product_id');
    }
    
    public function brand()
    {
        return $this->belongsTo(Brand::class,'brand_id');
    }
    public function brandDesc()
    {
        return $this->belongsTo(BrandDesc::class,'brand_id','brand_id');
    }
    
    public function member()
    {
        return $this->belongsTo(Member::class,'member_id','member_id');
    }
    public function comments()
    {
        return $this->morphMany(Comment::class, 'comment')->whereNull('parent_id');
    }
    public function productPicture()
    {
        return $this->hasMany(ProductPicture::class,'pid','product_id');
    }
    public function productGroups()
    {
        return $this->hasMany(ProductGroup::class,'product_main','product_id');
    }
    public function orderDetails()
    {
        return $this->hasMany(OrderDetails::class,'item_id','product_id');
    }
    public function price()
    {
        return $this->hasMany(Price::class,'product_id','product_id');
    }
    
    
}
