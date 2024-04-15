<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class AdminMenu extends Model
{
    use HasFactory, Notifiable, HasRoles;
    protected $table = 'admin_menu';
    protected $fillable = [
        'title_vi',
        'link',
        'status',
        'position',
        'display',
    ];
    
}
