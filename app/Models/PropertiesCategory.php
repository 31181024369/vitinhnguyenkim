<?php

namespace App\Models;

use App\Models\Properties;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PropertiesCategory extends Model
{
    use HasFactory;
    protected $table = 'properties_category';
    protected $primaryKey = 'id';
    protected $fillable = [
        'cat_id', 'properties_id','parentid'
    ];
    public function properties()
    {
        return $this->hasOne(Properties::class, 'id','properties_id');
    }
}
