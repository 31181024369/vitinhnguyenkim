<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class AdminPermission extends Model
{
    use HasFactory, Notifiable, HasRoles;
    protected $table = 'admin_permission';
    protected $fillable = [
        'g_name',
        'title_vi',
        'title_en',
        'module',
        'act',
        'text_option',
        'menu_order',
        'display',
    ];
    
}
