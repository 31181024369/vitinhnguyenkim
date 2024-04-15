<?php

namespace App\Models;

use App\Models\ProductProperties;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Price extends Model
{
    use HasFactory;
    protected $table = 'price';
    protected $primaryKey = 'id';
    protected $fillable = [
        'cat_id', 'product_id', 'price', 'price_old', 'picture', 'main','technology'
    ];
    public function propertiesProduct()
    {
        return $this->hasMany(ProductProperties::class, 'price_id', 'id');
    }
}

