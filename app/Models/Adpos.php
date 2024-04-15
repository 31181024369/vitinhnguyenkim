<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Advertise;

class Adpos extends Model
{
    
    protected $table = 'ad_pos';
    protected $primaryKey = 'id_pos';
    use  HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'cat_id',
        'title',
        'width',
        'height',
        'n_show',
        'description',
        'display',
        'menu_order',
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
    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($advertise) {
            $advertise->advertise()->delete();
        });
    }
    public function advertise()
    {
        return $this->hasMany(Advertise::class,'pos','id_pos');
    }
}
