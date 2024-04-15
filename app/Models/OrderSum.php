<?php

namespace App\Models;

use App\Models\OrderDetail;
use App\Models\OrderStatus;
use App\Models\CouponDesUsing;
use App\Models\Member;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderSum extends Model
{
    use HasFactory;
    protected $table = 'order_sum';
    protected $primaryKey = 'order_id';
    protected $timestamp = true;
    protected $fillable = [
        'status', 'ship_date','comment','phieu_xuat','date_order_status1',
        'date_order_2	','date_order_status3','date_order_status4','date_order_status5',
        'date_order_status6','date_order_status7'
    ];
    public function orderStatus()
    {
        return $this->belongsTo(OrderStatus::class, 'status','status_id');
    }
    public function orderDetail()
    {
        return $this->hasMany(OrderDetail::class, 'order_id','order_id');
    }
    public function coupondesusing()
    {
        return $this->hasMany(CouponDesUsing::class, 'IDOrderCode','order_code');
    }
    public function member()
    {
        return $this->belongsTo(Member::class, 'mem_id','mem_id');
    }
}
