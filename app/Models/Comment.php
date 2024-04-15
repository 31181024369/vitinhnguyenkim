<?php

namespace App\Models;

use App\Models\Member;
use App\Models\ProductDesc;
use App\Models\NewsDesc;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
 {
    use HasFactory;
    protected $table = 'comment';
    protected $primaryKey = 'comment_id';
    protected $filable = [
        'post_id',
        'parentid',
        'mem_id', 'name', 'email', 'content', 'avatar', 'mark', 'address_IP',
        'display', 'date_post', 'date_update', 'adminid','phone'
    ];
    public static function search( $query )
 {
        $client = ClientBuilder::create()
        ->setHosts( config( 'database.connections.elasticsearch.hosts' ) )
        ->build();

        $params = [
            'index' => 'content', // Thay 'your_index_name' bằng tên index thực tế
            'body' => [
                'query' => [
                    'match' => [
                        'content' => $query,
                    ],
                ],
            ],
        ];

        $response = $client->search( $params );

        return $response[ 'hits' ][ 'hits' ];
    }

    public function member()
 {
        return $this->belongsTo( Member::class, 'mem_id', 'mem_id' );
    }

    public function subcomments()
    {
        return $this->hasOne( Comment::class, 'parentid', 'comment_id' );
    }
    public function productDesc()
    {
        return $this->hasOne(ProductDesc::class, 'product_id', 'post_id');
    }
    public function newsDesc()
    {
        return $this->hasOne( NewsDesc::class, 'news_id', 'post_id' );
    }
}
