<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatisticUserOnline extends Model
{
    use HasFactory;
    protected $table = 'statistics_useronline';
    protected $primaryKey = 'id';
    protected $fillable = [ 'ip', 'created','referred','date','agent','platform','version','location','timestamp'];
}