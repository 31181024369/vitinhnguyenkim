<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Properties;
class PropertiesChildCate extends Model
{
    use HasFactory;
    protected $table = 'properties_child_category';
    protected $primaryKey = 'id';
    protected $fillable = [
        'cat_id', 'properties_id','parent_id'
    ];
    public function properties()
    {
        return $this->hasOne(Properties::class, 'id','properties_id');
    }
}
