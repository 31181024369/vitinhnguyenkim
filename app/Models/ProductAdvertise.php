<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ProductAdvertise extends Model
{
    protected $table = 'product_advertise';
    protected $primaryKey = 'id';
    use  HasFactory;
   
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'itemID',
        'type',
        'pos',
        'picture',
        'link',
        'title',
        'description',
        'target',
        'height',
        'width',
        'display',
        'menu_order',
        'date_post',
        'date_update',
        'lang',
        'adminid'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

}
