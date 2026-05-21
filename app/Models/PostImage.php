<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostImage extends Model
{
    protected $table = 'post_images';
    protected $fillable = ['post_id', 'image_url', 'sort_order'];
    public $timestamps = false; // только created_at

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
