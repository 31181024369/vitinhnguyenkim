<?php

namespace App\Models;

use App\Models\Product;
use App\Models\Brand;
use App\Models\ProductDesc;
use App\Models\CategoryDesc;
use App\Models\ProductCatOption;
use App\Models\Properties;
use App\Models\PropertiesCategory;
use App\Models\PropertiesChildCate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Category extends Model
{
    protected $table = 'product_category';
    protected $primaryKey = 'cat_id';
    use  HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cat_code',
        'parentid',
        'picture',
        'color',
        'psid',
        'is_default',
        'is_buildpc',
        'show_home',
        'list_brand',
        'list_price',
        'list_support',
        'menu_order',
        'views',
        'display',
        'date_post',
        'date_update',
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

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($category) {
            $category->categoryDesc()->delete();
        });
    }

    public function categoryProductBrand()
    {
        return $this->hasOne(Brand::class, 'list_brand');
    }

    public function categoryDesc()
    {
        return $this->hasOne(CategoryDesc::class, 'cat_id');
    }

    public function product()
    {
        return $this->hasMany(Product::class, 'cat_id','cat_id');   
    }

    public function productsCatList()
    {
        return $this->hasMany(Product::class, 'cat_list','cat_id');   
    }

    public function productCate()
    {
        return $this->hasMany(Product::class,'cat_id','cat_id');
    }
   
    public function subCategories()
    {
        return $this->hasMany(Category::class, 'parentid', 'cat_id');
    }
    public function catOption()
    {
        return $this->hasMany(ProductCatOption::class, 'cat_id', 'cat_id')->orderBy('menu_order', 'ASC');
    }
    public function catOptionParentid()
    {
        return $this->hasMany(ProductCatOption::class, 'cat_id', 'cat_id');
    }
    public function catProperties()
    {
        return $this->hasMany(PropertiesCategory::class, 'cat_id', 'cat_id');   
    }
    public function catChildProperties()
    {
        return $this->hasMany(PropertiesChildCate::class, 'cat_id', 'cat_id');   
    }
    
}
