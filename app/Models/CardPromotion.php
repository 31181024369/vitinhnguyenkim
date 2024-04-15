<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardPromotion extends Model
{
    use HasFactory;
    protected $table = 'card_promotion';
    protected $primaryKey = 'id';
    protected $fillable = [
        'order_id','value'
    ];
}
