<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Traits\HasPermissions;
// use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Role;


class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles,HasApiTokens,HasPermissions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guard = 'admin';
    protected $table = 'user';
    // protected $primaryKey = 'adminid';
    protected $fillable = [
         'name', 'email', 'password','department_id','displayName','displayAdmin','avatar',
         'status','phone'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    protected $guard_name = 'web';
    public function department()
    {
        return $this->hasOne(Department::class);
    }
    
    public function isAdmin()
    {
        return $this->admin; // this looks for an admin column in your users table
    }
    // public function roles(){
    //     return $this->belongsToMany(Role::class,'user_role');
    // }
    // public function hasRole($role){
    //     return $this->roles->constant('name',$role);
    // }
    // public function hasPermission($permission){
    //     foreach($this->roles as $role){
    //         if($role->permissions->where('slug',$permission)->count()>0){
    //             return true;
    //         }
    //     }
    //     return false;
    // }

}
