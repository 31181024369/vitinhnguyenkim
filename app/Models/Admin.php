<?php

namespace App\Models;


use App\Models\Member;
use App\Models\AdminGroup;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Role;
use App\Models\Permission;


class Admin extends Authenticatable
{
    use HasFactory;
    use HasApiTokens;
    
    protected $table = 'admin';
    protected $primaryKey = 'adminid';
    protected $fillable = [
        'username',
        'password',
        'email'
    ];
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
    public function AdminGroup()
    {
        return $this->hasOne(AdminGroup::class,'id','level');
    }
    public function member(){
       return $this->hasMany(Member::class,'company','adminid');
    }

    
    

    public function department()
    {
        return $this->hasOne(Department::class,'id','depart_id');
    }
    
    public function isAdmin()
    {
        return $this->admin; // this looks for an admin column in your users table
    }
    public function roles(){
        return $this->belongsToMany(Role::class,'admin_role','admin_id','role_id');
    }

    public function permissions(){
        return $this->belongsToMany(Permission::class,'admin_permission','admin_id','permission_id');
    }
    public function hasRole($role){
        return $this->roles->constant('name',$role);
    }
    public function hasPermission($permission){
        foreach($this->roles as $role){
            if($role->permissions->where('slug',$permission)->count()>0){
                return true;
            }
        }
        return false;
    }

   
}
