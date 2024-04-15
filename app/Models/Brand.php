<?php

namespace App\Models;

use App\Models\Product;
use App\Models\Category;
use App\Models\BrandDesc;
use App\Models\ProductDesc;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Brand extends Model
{
    
    protected $table = 'product_brand';
    protected $primaryKey = 'brand_id';
    use  HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cat_id',
        'picture',
        'focus',
        'menu_order',
        'views',
        'display',
        'date_post',
        'date_update',
        'adminid',
        'created_at',
        'updated_at'        
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
        static::deleting(function ($brand) {
            $brand->brandDesc()->delete();
        });
    }
    public function brandDesc()
    {
        return $this->hasOne(BrandDesc::class,'brand_id','brand_id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class,'brand_id','brand_id');
    }
    public function category()
    {
        return $this->hasMany(Category::class,'cat_id','cat_id');
    }
}
