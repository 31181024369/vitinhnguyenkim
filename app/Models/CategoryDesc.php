<?php

namespace App\Models;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class CategoryDesc extends Model
{
    protected $table = 'product_category_desc';
    protected $primaryKey = 'id';
    use  HasFactory;
   
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cat_id',
        'cat_name',
        'home_title',
        'description',
        'frendly_url',
        'friendly_title',
        'metakey',
        'metadesc',
        'lang',
        'script_code'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    

    public function category()
    {
        return $this->belongsTo(Category::class, 'cat_id');
    }
}
