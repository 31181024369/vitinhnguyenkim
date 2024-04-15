<?php

namespace App\Models;

use App\Models\Price;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Properties;
use App\Models\PropertiesValue;

class ProductProperties extends Model
{
    use HasFactory;
    protected $table = 'product_properties';
    protected $primaryKey = 'id';
    protected $fillable = [
        'pv_id', 'properties_id', 'price_id', 'description'
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

