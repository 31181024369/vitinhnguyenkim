<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin;
class infoVoucher extends Model
{
    use HasFactory;
    protected $table = 'infovoucher';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name','phone','code','count','admin_id	','mapx'
    ];
    public function admin()
    {
        return $this->hasOne(Admin::class,'adminid','admin_id');
    }

}
