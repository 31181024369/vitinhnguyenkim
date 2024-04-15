<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatisticsVisit extends Model
{
    use HasFactory;
    protected $table = 'statistics_visit';
    protected $primaryKey = 'id';
    protected $fillable = [ 'last_visit', 'last_counter','visit' ];
}