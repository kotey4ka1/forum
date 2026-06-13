<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForumSection extends Model
{
    protected $table = 'forum_sections';
    protected $fillable = ['name', 'description', 'image_url'];  // ← добавили image_url

    public function posts()
    {
        return $this->hasMany(Post::class, 'forum_section_id');
    }
}
