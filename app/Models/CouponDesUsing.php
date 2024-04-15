<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrderSum;
use App\Models\CouponDes;

 
class CouponDesUsing extends Model
{
    use HasFactory;
    protected $table = 'coupondesusing';
    protected $fillable = [
            'IDuser',
            'idCouponDes ',
            'DateUsingCode',
            'IDOrderCode',
            'MaCouponUSer',
        ];
    public function couponDesUsing()
    {
        return $this->hasMany(CouponDesUsing::class,'idCouponDes','idCouponDes');
    }
    // public function couponDes()
    // {
    //     return $this->hasOne(CouponDes::class,'idCouponDes','idCouponDes');
    // }
    

   
}
