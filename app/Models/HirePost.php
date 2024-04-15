<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HirePost extends Model
{
    use HasFactory;
    protected $table = 'hire_category';
    protected $primaryKey = 'id';
    protected $fillable = [ 'name', 'salary','address','experience','deadline','information','rank','number','form','degree','department','slug','meta_keywords','meta_description','hire_cate_id','image'];
   
}
