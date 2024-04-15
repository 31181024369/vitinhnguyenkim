<?php

namespace App\Models;
use App\Models\Products;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ProductDescs extends Model
{
    protected $table = 'product_descs';
    protected $primaryKey = 'id';
    use  HasFactory;
   
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'title',
        'description',
        'gift_desc',
        'video_desc',
        'tech_desc',
        'option',
        'short',
        'start_date_promotion',
        'end_date_promotion',
        'status_promotion',
        'short_code',
        'key_search',
        'friendly_url',
        'friendly_title',
        'metakey',
        'metadesc',
        'lang'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // public function product()
    // {
    //     return $this->belongsTo(Product::class, 'product_id','product_id');
    // }
    public function products()
    {
        return $this->belongsTo(Products::class, 'id','product_id');
    }

}
