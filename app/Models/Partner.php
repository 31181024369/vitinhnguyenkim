<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PartnerNews;
use App\Models\PartnerImage;
class Partner extends Model
{
    use HasFactory;
    protected $table = 'partner';
    protected $primaryKey = 'id';
    protected $fillable = [ 'logo', 'namePartner','desPartner','url'];

    public function PartnerNews()
    {
        return $this->hasMany(PartnerNews::class, 'partner_id','id');
    }
    public function PartnerImage() {
        return $this->hasMany( PartnerImage::class, 'partner_id', 'id' );
    }

}
