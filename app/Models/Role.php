<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Permission;

class Role extends Model
{
    use HasFactory;
    protected $table = 'roles';
   
    protected $primaryKey = 'id';
    protected $fillable = [ 'name', 'guard_name','parent_role'];

    public function permissions(){
        return $this->belongsToMany(Permission::class,'role_permission');
    }
}
