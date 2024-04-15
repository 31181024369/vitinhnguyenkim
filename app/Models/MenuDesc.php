<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuDesc extends Model
{
    use HasFactory;
    protected $table = 'menu_desc';
    protected $primaryKey = 'id';
    protected $fillable = [
        'menu_id ', 'name','title','link','lang'
    ];
}