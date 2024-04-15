<?php

namespace App\Models;
use App\Models\Adpos;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Advertise extends Model
{
    protected $table = 'advertise';
    protected $primaryKey = 'id';
    use  HasFactory;
   
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'picture',
        'pos',
        'id_pos',
        'width',
        'height',
        'link',
        'target',
        'module_show',
        'description',
        'menu_order',
        'display',
        'lang',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    

}
