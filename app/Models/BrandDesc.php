<?php

namespace App\Models;
use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class BrandDesc extends Model
{
    protected $table = 'product_brand_desc';
    protected $primaryKey = 'id';
    use  HasFactory;
   
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'brand_id',
        'title',
        'description',
        'frendly_url',
        'friendly_title',
        'metakey',
        'metadesc',
        'lang',
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

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id','brand_id');
    }

}
