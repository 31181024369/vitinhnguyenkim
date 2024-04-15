<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerIntroduce extends Model
{
    use HasFactory;
    protected $table = 'partner_introduce';
    protected $primaryKey = 'id';
    protected $fillable = [ 'thanks','introduce'];
}
