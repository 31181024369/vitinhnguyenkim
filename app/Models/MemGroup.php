<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemGroup extends Model
{
    use HasFactory;
    protected $table ='mem_group';
    protected $fillable = [
        'g_name'
    ];
}
