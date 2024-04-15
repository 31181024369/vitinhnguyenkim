<?php

namespace App\Models;
use App\Models\PromotionDesc;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;
    protected $table = 'promotion';
    protected $primaryKey = 'promotion_id';
    protected $fillable = [ 'picture', 'focus','focus_order', 'views','display','menu_order','adminid','date_start_promotion','date_end_promotion' ];
    
    // public function promotionDesc()
    // {
    //     return $this->belongsTo(PromotionDesc::class, 'promotion_id');
    // }
    public function promotionDesc()
    {
        return $this->hasOne(PromotionDesc::class, 'promotion_id');
    }
}
