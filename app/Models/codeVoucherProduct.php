<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class codeVoucherProduct extends Model
{
   
    use HasFactory;
    protected $table = 'codevoucherproduct';
    protected $primaryKey = 'id';
    protected $fillable = [
        'code'
    ];
}
