<?php

namespace App\Models;

use App\Models\adminPermission;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class AdminGroup extends Model
{
    use HasFactory, Notifiable, HasRoles;
    protected $table = 'admin_group';
    protected $primaryKey = 'id';
    protected $fillable = [
        'title',
        'roles',
        'permission',
        'menu_order',
        'is_default'
    ];
}
