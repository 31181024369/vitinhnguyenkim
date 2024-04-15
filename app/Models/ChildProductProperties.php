<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildProductProperties extends Model
{
    use HasFactory;
    protected $table = 'child_product_properties';
    protected $primaryKey = 'id';
    protected $fillable = [
        'pv_id', 'properties_id', 'price_id','cat_id', 'description'
    ];
    public function price()
    {
        return $this->belongsTo(Price::class, 'price_id', 'id');
    }
    public function properties()
    {
        return $this->belongsTo(Properties::class, 'properties_id', 'id');
    }
    public function propertiesValue()
    {
        return $this->belongsTo(PropertiesValue::class, 'pv_id', 'id');
    }
}
