<?php

namespace App\Models;
use App\Models\FaqsDesc;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faqs extends Model
{
    use HasFactory;
    protected $table = 'faqs';
    protected $primaryKey = 'faqs_id';
    protected $fillable = [
        'cat_id', 'cat_list','poster','email_poster','phone_poster','answer_by','views','display','menu_order','adminid'
    ];
    public function faqsDesc()
    {
        return $this->hasOne(FaqsDesc::class, 'faqs_id');;
    }
}