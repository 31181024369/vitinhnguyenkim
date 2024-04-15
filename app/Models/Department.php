<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use HasFactory;
    protected $table = 'department';
    protected $fillable = ['name'];
    public function user()
    {
        return $this->hasMany(User::class);
    }
  
 

}
