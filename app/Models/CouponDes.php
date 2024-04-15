<?php

namespace App\Models;

use App\Models\CouponDesUsing;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Coupon;

class CouponDes extends Model
{
    use HasFactory;
    protected $table = 'coupondes';
    protected $primaryKey = 'idCouponDes';
    protected $fillable = [
        'idCouponDes ',
        'MaCouponDes',
        'SoLanSuDungDes',
        'SoLanConLaiDes',
        'StatusDes',
        'DateCreateDes',
        'idCoupon',
        'Max',
    ];
    // public function coupon()
    // {
    //     return $this->hasMany(Coupon::class);
    // }
    public function couponDesUsing()
    {
        return $this->hasMany(CouponDesUsing::class,'idCouponDes','idCouponDes');
    }
    // public function coupon()
    // {
    //     return $this->belongsTo(Coupon::class,'idCouponDes','idCouponDes');
    // }
}
