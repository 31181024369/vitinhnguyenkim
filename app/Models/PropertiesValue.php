<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// Check if the class already exists before declaring
if (!class_exists('App\Models\PropertiesValue')) {
    class PropertiesValue extends Model
    {
        use HasFactory;
        protected $table = 'properties_value';
        protected $primaryKey = 'id';
        protected $fillable = [
            'properties_id', 'name'
        ];
    }
}
