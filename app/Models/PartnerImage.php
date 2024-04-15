<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerImage extends Model
{
    use HasFactory;
    protected $table = 'partnerimage';
    protected $primaryKey = 'id';
    protected $fillable = [ 'partner_id', 'pic_name', 'picture' ];
}
