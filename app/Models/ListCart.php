<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ListCart extends Model
{
    protected $table = "list_cart";
    public $timestamps = true;
    use  HasFactory;
   
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mem_id',
        'mem_name',
        'md5_id',
        'product_id',
        'stock',
        'quality',
        'title',
        'status',
        'price',

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function member()
    {
        return $this->belongsTo(Member::class,'mem_id');
    }
    public function productDesc()
    {
        return $this->belongsTo(ProductDesc::class,'product_id');
    }

}
