<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;
    protected $table = 'payment_method';
    protected $primaryKey = 'payment_id';
    protected $fillable = [
        'payment_id',
        'title',
        'description',
        'name','options','is_config','menu_order','display','lang','date_post','date_update','adminid'
    ];
}
