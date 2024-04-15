<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrawData extends Model
{
    use HasFactory;
    protected $table = 'craw_data';
    public $timestamps = false;
    protected $fillable = ['name','price'];
}
