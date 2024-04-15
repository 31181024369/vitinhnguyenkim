<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PartnerNews;
class PartnerNews extends Model
{
    use HasFactory;
    protected $table = 'partner_news';
    protected $primaryKey = 'id';
    protected $fillable = [ 'partner_id','picture','title','description','short','friendly_url',
    'friendly_title','metakey','metadesc'];
    public function partner()
    {
        // return $this->hasOne(PartnerNews::class,'id','partner_id');
        return $this->belongsTo(Partner::class, 'partner_id','id');
       
    }

}
