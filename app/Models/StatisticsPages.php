<?php

namespace App\Models;
use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatisticsPages extends Model
{
    use HasFactory;
    protected $table = 'statistics_pages';
    protected $primaryKey = 'id_static_page';
    protected $fillable = [ 'uri', 'date','count', 'id','module','action','friendly_url' ];

    public function member()
    {
        return $this->hasOne(Member::class, 'mem_id','id');
    }
}