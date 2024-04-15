<?php

namespace App\Models;

use App\Models\OrderSum;
use App\Models\Admin;
use App\Models\StatisticsPages;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Member extends  Authenticatable
{
    
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'members';
    protected $primaryKey = 'mem_id';
  

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mem_group','username','mem_code', 'email', 'password', 'activate_code', 'address', 'company', 'full_name', 'gender', 'birthday', 'avatar',
        'phone','buildpc','newsletter', 'date_join', 'last_login', 'm_status', 'mem_point', 'mem_point_use', 'api_type', 'api_user', 'api_pass', 'menu_order',
        'Tencongty','Masothue','Diachicongty', 'Sdtcongty', 'emailcty', 'idmacoupon', 'MaKH', 'remember_token','MaKHDinhDanh','status','ward',
        'district','city_province','password_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function repurchase()
    {
        return $this->hasMany(OrderSum::class,'mem_id')->where('status',5);
    }
    public function adminSupport(){
        return $this->hasOne(Admin::class,'adminid','company');
    }
}
