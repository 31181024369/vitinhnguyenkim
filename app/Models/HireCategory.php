<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\HirePost;
class HireCategory extends Model
{
    use HasFactory;
    protected $table = 'hire_category';
    protected $primaryKey = 'id';
    protected $fillable = [ 'name', 'slug'];
    public function hireCategory()
    {
        return $this->hasMany(HirePost::class,'hire_cate_id','id');
    }



}
