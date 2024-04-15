<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponWholeSaleCustomerName extends Model
{
    use HasFactory;
    protected $table = 'coupon_wholesale_customer_name';
    protected $primarikey = 'id';
    protected $fillable = [
        'mem_id',
        'username'
    ];
}
