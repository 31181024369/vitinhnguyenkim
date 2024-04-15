<?php

namespace App\Models;
use App\Models\News;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsDesc extends Model
{
    use HasFactory;
    protected $table = 'news_desc';
    protected $primaryKey = 'id';
    protected $fillable = [ 'news_id', 'product_id','title', 'description','short','friendly_url','friendly_title','metakey','metadesc','lang' ];
    public function news()
    {
        return $this->belongsTo(News::class, 'news_id');
    }
    public function comment()
    {
        return $this->hasMany(Comment::class, 'post_id', 'news_id' )->where('display',1)->where('parentid',0);
    }
}
